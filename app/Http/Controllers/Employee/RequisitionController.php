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
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refineDescription(Request $request): JsonResponse
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The description must be between 10 and 1000 characters.',
                'refined_text' => $request->description
            ], 422);
        }

        $rawDescription = $request->input('description');
        Log::info("Attempting to refine description for user: " . Auth::guard('employee')->id());

        try {
            // 2. Call the AI Service
            $refinedText = $this->aiService->refineDescription($rawDescription);

            // 3. Handle AI service errors
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
     * Handles the form submission and stores the new requisition.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'required|string|min:10|max:1000',
            'urgency' => 'required|in:low,medium,high,critical',
        ]);

        // 2. Data Preparation (MOCK Data Persistence)
        $requisitionData = [
            'employee_id' => Auth::guard('employee')->id(), // Get the logged-in employee's ID
            'title' => $request->title,
            'quantity' => $request->quantity,
            'description' => $request->description, // This will be the AI-refined (or original) text
            'urgency' => $request->urgency,
            'status' => 'Pending', // Default status for new requisition
            'created_at' => now()->toDateTimeString(),
        ];

        // LOG the data to confirm it was captured (In a real app, this would be an ORM call: Requisition::create($requisitionData);)
        Log::info('New Requisition Submitted:', $requisitionData);

        // 3. Redirect and Status Message
        return redirect()->route('employee.dashboard')->with('status', 'Requisition "' . $requisitionData['title'] . '" submitted successfully! It is now pending approval.');
    }
}