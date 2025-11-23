<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RequisitionAIService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RequisitionController extends Controller
{
    protected $aiService;

    /**
     * Inject the RequisitionAIService via the constructor.
     */
    public function __construct(RequisitionAIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Show the form for creating a new requisition.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('employee.requisition.create');
    }

    /**
     * Handle the AJAX request to refine the requisition description using AI.
     */
    public function refineDescription(Request $request): JsonResponse
    {
        // ... (existing refineDescription logic)
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The description must be between 10 and 1000 characters.',
            ], 422);
        }

        $rawDescription = $request->input('description');

        try {
            // 2. Call AI Service
            $refinedText = $this->aiService->refine($rawDescription);

            // 3. Handle Service Response
            if (str_starts_with($refinedText, 'AI service unavailable:') || str_starts_with($refinedText, 'Failed to refine description')) {
                 Log::error("AI Refinement Error: " . $refinedText);
                 return response()->json([
                    'success' => false,
                    'message' => 'AI Service Error: Could not generate a summary. Check application logs.',
                    'refined_text' => $rawDescription // Return original text as fallback
                ], 500);
            }

            // 4. Success Response
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
                'refined_text' => $rawDescription // Return original text as fallback
            ], 500);
        }
    }

    /**
     * Handle the form submission and redirect to the specific requisition page.
     */
    public function store(Request $request)
    {
        // 1. **Validation & Store Logic (Simulated)**
        // In a real application, you would validate and save the requisition here.
        // $requisition = Requisition::create($validatedData);

        // **SIMULATION**: We use a hardcoded ID for redirection demonstration
        $requisitionId = 123; 
        
        // 2. Redirect to the new requisition's view page (using the new route 'employee.requisitions.show')
        return redirect()->route('employee.requisitions.show', $requisitionId)
            ->with('status', "Requisition #{$requisitionId} submitted successfully!");
    }

    /**
     * Show a specific requisition detail page. (Placeholder)
     * * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // This is a placeholder for the actual requisition detail view.
        // In a real application: $requisition = Requisition::findOrFail($id);
        return view('employee.requisition.show', ['requisitionId' => $id]);
    }
}