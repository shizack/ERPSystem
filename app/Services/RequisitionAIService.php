<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service class to handle interactions with the Gemini API for requisition refinement.
 */
class RequisitionAIService
{
    protected $model = 'gemini-2.5-flash-preview-09-2025';
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        // The API key is set to an empty string; the execution environment will handle injection.
        $this->apiKey = '';
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";
    }

    /**
     * Refines a raw job/requisition description into a professional summary using the Gemini API.
     *
     * @param string $rawDescription The raw, unrefined text from the user.
     * @return string The refined text, or a detailed error message string if the API call fails.
     */
    public function refine(string $rawDescription): string
    {
        // Define the instruction for the AI model's persona and task
        $systemInstruction = "You are an expert procurement and human resources specialist. Your task is to take a raw, informal, or brief requisition description and refine it into a clear, professional, concise, and complete summary suitable for formal procurement or job posting. Focus on essential needs, quantity, and urgency. Do not include any introductory phrases like 'Here is the refined description:'—just provide the refined text.";

        // Construct the payload for the API call
        $payload = [
            'contents' => [
                ['parts' => [
                    ['text' => "Refine the following requisition description: \"{$rawDescription}\""],
                ]],
            ],
            // Enable Google Search grounding for up-to-date context
            'tools' => [
                ['google_search' => new \stdClass()],
            ],
            // Set the system instruction for guidance
            'config' => [
                'systemInstruction' => $systemInstruction,
            ],
        ];

        try {
            // Implement exponential backoff for retries
            $maxRetries = 3;
            $delay = 1;

            for ($i = 0; $i < $maxRetries; $i++) {
                // Post the request to the Gemini API
                $response = Http::timeout(30)->post($this->apiUrl, $payload);

                if ($response->successful()) {
                    $result = $response->json();

                    // Check for the generated text in the response structure
                    $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

                    if ($text) {
                        return trim($text); // Return the clean, refined text
                    }
                    // If successful but no text was returned, stop trying
                    break;
                }

                // If not successful and not the last retry, wait and retry
                if ($i < $maxRetries - 1) {
                    sleep($delay);
                    $delay *= 2; // Double the delay
                }
            }

            // If the loop finishes without returning a valid response
            $errorMessage = $response->body() ?? 'No response body.';
            Log::error('AI Service Error: Failed to get a successful response from the API.', ['response' => $errorMessage]);
            return 'AI service unavailable: Failed to refine description after multiple attempts.';

        } catch (\Exception $e) {
            // Catch critical errors like network failure or malformed JSON
            Log::error('AI Service Critical Exception: ' . $e->getMessage());
            return 'AI service unavailable: A critical network or processing error occurred.';
        }
    }
}