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
        // 1. Get API Key from config (which loads from .env)
        // Using config() is more reliable than env() after config caching
        $this->apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY');

        // Critical Check: Log an error if the API key is missing
        if (empty($this->apiKey)) {
            Log::error("GEMINI_API_KEY is missing from environment or not loaded.");
        }
    }

    /**
     * Refines the raw job description using the Gemini API.
     *
     * @param string $rawDescription The user-provided raw description text.
     * @param string|null $itemName The name of the item being requested.
     * @param int|null $quantity The quantity being requested.
     * @return string The refined text, or an error message string.
     */
    public function refineDescription(string $rawDescription, ?string $itemName = null, ?int $quantity = null): string
    {
        // 1. Check if the API key is available before making the request
        if (empty($this->apiKey)) {
            return "AI service unavailable: GEMINI_API_KEY is not configured.";
        }

        // 2. Define API Endpoint and Model
        // Using gemini-2.5-flash which is the stable Gemini Flash model
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $this->apiKey;

        // 3. Define the prompt with instruction embedded
        $contextInfo = [];
        if ($itemName) {
            $contextInfo[] = "Item: $itemName";
        }
        if ($quantity) {
            $contextInfo[] = "Quantity: $quantity";
        }
        
        $context = !empty($contextInfo) ? "\n" . implode("\n", $contextInfo) . "\n" : "";
        
        $promptText = "Refine this requisition description to be clear, concise, and professional. Start with 'Will be used for' and describe the purpose and add a little detail. Do not suggest options or add extra information. Keep it straightforward and factual. Employees of a resort and hotel will use this.{$context}\nDescription: {$rawDescription}";

        // 4. Construct the Request Payload
        $payload = [
            'contents' => [
                ['parts' => [['text' => $promptText]]],
            ]
        ];

        // 5. Execute cURL Request (MOCK/Placeholder for actual Guzzle/HTTP Client)
        try {
            Log::info("AI Refinement: Starting API request", ['description_length' => strlen($rawDescription)]);
            
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 second timeout
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Add this for testing

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            Log::info("AI Refinement: API Response", [
                'http_code' => $httpCode,
                'curl_error' => $error,
                'response_length' => strlen($response ?? '')
            ]);

            if ($error) {
                // Network or cURL error
                Log::error("AI Refinement: cURL Error - " . $error);
                return "AI service unavailable: Network error - " . $error;
            }

            if ($httpCode !== 200) {
                // API HTTP error
                Log::error("AI API Error (HTTP $httpCode): " . substr($response, 0, 500));
                
                //extract an error message from the response body if it's JSON
                $decodedResponse = json_decode($response, true);
                $errorDetails = $decodedResponse['error']['message'] ?? "Unknown API Error.";
                
                //debugging
                Log::error("AI API Full Error Details", ['response' => $decodedResponse]);

                // Check for a specific API Key error structure 400 or 403 status
                if (str_contains($errorDetails, 'API_KEY_INVALID') || $httpCode === 400 || $httpCode === 403) {
                     return "AI service unavailable: Invalid API Key or access denied. Status: $httpCode. Details: $errorDetails";
                }

                return "AI service unavailable: API returned HTTP $httpCode. Details: $errorDetails";
            }

            // 6. Decode Response and Extract Text
            $result = json_decode($response, true);
            
            // Log the full response structure for debugging
            Log::info("AI API Success Response", ['response_structure' => $result]);

            // Check for safety filter blocks
            if (isset($result['candidates'][0]['finishReason']) && 
                $result['candidates'][0]['finishReason'] === 'SAFETY') {
                Log::warning("AI Refinement: Content blocked by safety filters");
                return "Failed to refine description: Content blocked by safety filters. Please try rephrasing.";
            }

            // Check if the response structure contains the generated text
            $refinedText = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($refinedText) {
                Log::info("AI Refinement Success: Generated text length: " . strlen($refinedText));
                return trim($refinedText);
            }

            // Fallback for cases where the structure is valid but no text was returned
            Log::warning("AI Refinement: Received valid response but no text candidate.", ['result' => $result]);
            return "Failed to refine description: No text output from AI.";

        } catch (\Exception $e) {
            Log::error("AI Refinement Service Exception: " . $e->getMessage());
            // Return a standardized error string for the controller to catch
            return "Failed to refine description: Critical service exception.";
        }
    }
}