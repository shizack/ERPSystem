<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Product;
use App\Models\Requisition;
use Illuminate\Http\Request;
use App\Services\RequisitionAIService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RequisitionController extends Controller
{
    protected $aiService;

    /**
     * Inject the RequisitionAIService via the constructor.
     */
    public function __construct(RequisitionAIService $aiService)
    {
        $this->aiService = $aiService;
        $this->middleware('auth:employee');
    }
    
    /**
     * Display a listing of the requisitions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $requisitions = \App\Models\Requisition::with(['product', 'requester'])
            ->where('requested_by', auth('employee')->id())
            ->latest()
            ->paginate(10);
            
        return view('employee.requisitions.index', compact('requisitions'));
    }

    /**
     * Show the form for creating a new requisition.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            // Get products from the database with available quantity
            $products = \App\Models\Product::with('category')
                ->where('quantity', '>', 0)
                ->orderBy('name')
                ->get();
                
            if ($products->isEmpty()) {
                \Log::warning('No products available for requisition');
                return redirect()->route('employee.dashboard')
                    ->with('warning', 'No products are currently available for requisition. Please check back later.');
            }
            
            // Format products for the form
            $formattedProducts = $products->map(function($product) {
                return [
                    'item_id' => $product->product_id, // Using product_id as the identifier
                    'name' => $product->name,
                    'item_code' => $product->product_id, // Using product_id as the item code
                    'unit' => $product->unit ?? 'pcs', // Default to 'pcs' if unit is not set
                    'category' => $product->category->name ?? 'General', // Get the category name
                    'quantity' => $product->quantity // Include the available quantity
                ];
            });
            
            return view('employee.requisition.create', [
                'products' => $formattedProducts,
                'items' => $formattedProducts // Keeping both for backward compatibility
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading requisition form', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('employee.dashboard')
                ->with('error', 'Error loading requisition form. Please try again later.');
        }
    }

    /**
     * Handle the AJAX request to refine the requisition description using AI.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refineDescription(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|min:10|max:1000',
            'product_id' => 'nullable|integer',
            'quantity' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The description must be between 10 and 1000 characters.',
                'refined_text' => $request->description
            ], 422);
        }

        $rawDescription = $request->input('description');
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        
        // Get product name if product_id is provided
        $productName = null;
        if ($productId) {
            $product = \App\Models\Product::find($productId);
            $productName = $product ? $product->name : null;
        }
        
        Log::info("Attempting to refine description for user: " . Auth::guard('employee')->id());

        try {
            $refinedText = $this->aiService->refineDescription($rawDescription, $productName, $quantity);
            
            Log::info("AI Refinement Result", ['result_preview' => substr($refinedText, 0, 100)]);

            if (str_starts_with($refinedText, 'AI service unavailable:') || 
                str_starts_with($refinedText, 'Failed to refine description')) {
                Log::error("AI Refinement Error: " . $refinedText);
                return response()->json([
                    'success' => false,
                    'message' => $refinedText, // Return the actual error message
                    'refined_text' => $rawDescription
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Description successfully refined by AI.',
                'refined_text' => $refinedText,
            ]);

        } catch (\Exception $e) {
            Log::error("AI Refinement Critical Exception: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'A critical server error occurred during AI processing.',
                'refined_text' => $rawDescription
            ], 500);
        }
    }

    /**
     * Store a newly created requisition in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        \Log::info('Store method called', $request->all());
        
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1|max:1000',
            'description' => 'required|string|min:10|max:1000'
        ]);

        \Log::info('Validation passed', $validated);

        try {
            // Find the product by product_id (which is a string)
            $product = \App\Models\Product::where('product_id', $validated['product_id'])->firstOrFail();
            \Log::info('Product found', ['product_id' => $product->product_id, 'name' => $product->name]);

            // Check if the requested quantity is available
            if ($product->quantity < $validated['quantity']) {
                return back()->withInput()
                    ->with('error', 'Insufficient stock. Only ' . $product->quantity . ' ' . $product->unit . ' available.');
            }

            // Create the requisition
            $requisition = new Requisition();
            $requisition->product_id = $validated['product_id'];
            $requisition->requested_by = auth('employee')->id();
            $requisition->quantity = $validated['quantity'];
            $requisition->description = $validated['description'];
            $requisition->status = 'pending'; // Default status

            if ($requisition->save()) {
                \Log::info('Requisition created successfully', [
                    'requisition_id' => $requisition->id,
                    'product_id' => $requisition->product_id,
                    'quantity' => $requisition->quantity
                ]);

                return redirect()->route('employee.requisitions.index')
                    ->with('success', 'Requisition #' . $requisition->id . ' submitted successfully!');
            } else {
                throw new \Exception('Failed to save requisition');
            }

        } catch (\Exception $e) {
            \Log::error('Error creating requisition', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return back()->withInput()
                ->with('error', 'Error submitting requisition: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified requisition.
     *
     * @param  \App\Models\Requisition  $requisition
     * @return \Illuminate\View\View
     */
    public function show(Requisition $requisition)
    {
        try {
            // Ensure the employee can only view their own requisitions
            if ($requisition->requested_by !== auth('employee')->id()) {
                abort(403, 'Unauthorized action.');
            }

            // Eager load relationships
            $requisition->load(['product', 'requester', 'approver']);

            return view('employee.requisitions.show', [
                'requisition' => $requisition,
                'statuses' => [
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected'
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error viewing requisition', [
                'requisition_id' => $id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('employee.requisitions.index')
                ->with('error', 'Requisition not found or you do not have permission to view it.');
        }
    }
}