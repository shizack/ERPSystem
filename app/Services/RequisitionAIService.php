<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Service to handle external AI API calls for refining requisition descriptions.
 * NOTE: For a production Laravel application, you would typically use an HTTP client
 * like Guzzle to make the API request instead of a simple placeholder.
 */
class RequisitionAIService
{
    /**
     * The Gemini API Key (loaded from the .env file).
     */
    protected $apiKey;

    public function __construct()
    {
        // 1. Get API Key from environment (.env)
        // We use the env() helper directly here. The key is now read from the .env file.
        $this->apiKey = env('GEMINI_API_KEY');

        // Critical Check: Log an error if the API key is missing
        if (empty($this->apiKey)) {
            Log::error("GEMINI_API_KEY is missing from environment or not loaded.");
        }
    }

    /**
     * Refines the raw job description using the Gemini API.
     *
     * @param string $rawDescription The user-provided raw description text.
     * @return string The refined text, or an error message string.
     */
    public function refineDescription(string $rawDescription): string
    {
        // 1. Check if the API key is available before making the request
        if (empty($this->apiKey)) {
            return "AI service unavailable: GEMINI_API_KEY is not configured.";
        }

        // 2. Define API Endpoint and Model
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=" . $this->apiKey;

        // 3. Define the System Instruction for the Model
        $systemPrompt = "You are an employee, and your want to request an item from the company inventory. Refine the description to be clear, concise, and formal asking for something. Make your reply short and straightforward, don't add any extra information.";

        // 4. Construct the Request Payload
        $payload = [
            'contents' => [
                ['parts' => [['text' => $rawDescription]]],
            ],
            'systemInstruction' => [
                'parts' => [['text' => $systemPrompt]]
            ]
        ];

        // 5. Execute cURL Request (MOCK/Placeholder for actual Guzzle/HTTP Client)
        try {
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 second timeout

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                // Network or cURL error
                return "AI service unavailable: Network error - " . $error;
            }

            if ($httpCode !== 200) {
                // API HTTP error
                Log::error("AI API Error (HTTP $httpCode): " . $response);
                // Try to extract an error message from the response body if it's JSON
                $errorDetails = json_decode($response, true)['error']['message'] ?? "Unknown API Error.";

                // Check for a specific API Key error structure (often 400 or 403 status)
                if (str_contains($errorDetails, 'API_KEY_INVALID') || $httpCode === 400 || $httpCode === 403) {
                     return "AI service unavailable: Invalid API Key or access denied. Status: $httpCode. Details: $errorDetails";
                }

                return "AI service unavailable: API returned HTTP $httpCode. Details: $errorDetails";
            }

            // 6. Decode Response and Extract Text
            $result = json_decode($response, true);

            // Check if the response structure contains the generated text
            $refinedText = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($refinedText) {
                return trim($refinedText);
            }

            // Fallback for cases where the structure is valid but no text was returned
            Log::warning("AI Refinement: Received valid response but no text candidate.");
            return "Failed to refine description: No text output from AI.";

        } catch (\Exception $e) {
            Log::error("AI Refinement Service Exception: " . $e->getMessage());
            // Return a standardized error string for the controller to catch
            return "Failed to refine description: Critical service exception.";
        }
    }
}