<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Added for debugging

class ArcadeQuizController extends Controller
{
    public function index()
    {
        return view('games.quiz'); 
    }

    public function generate()
    {
        // 1. Prevent Timeout
        set_time_limit(120); 

        // 2. Check API Key
        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            Log::error("Gemini Error: API Key is missing.");
            return response()->json(['error' => 'API Key is missing in .env'], 500);
        }

        $prompt = "Generate 10 multiple-choice questions about Islamic basics suitable for primary school students. 
        Topics: 5 Pillars of Islam, 6 Pillars of Iman, Angels, Prophets, and Solat.
        
        RETURN ONLY RAW JSON. NO MARKDOWN. NO BACKTICKS.
        Format:
        [
            {
                \"question\": \"Question text\",
                \"options\": [\"A\", \"B\", \"C\", \"D\"],
                \"correct_answer\": \"The correct option\",
                \"topic\": \"Topic Name\"
            }
        ]";

        try {
            // 3. Send Request (With SSL Verification Disabled for Localhost)
            $response = Http::withoutVerifying()
    ->withHeaders(['Content-Type' => 'application/json'])
    ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
        'contents' => [['parts' => [['text' => $prompt]]]]
    ]);

            // 4. Check if Google rejected it
            if ($response->failed()) {
                Log::error("Gemini API Error: " . $response->body());
                return response()->json(['error' => 'Google API Refused connection.'], 500);
            }

            $data = $response->json();
            
            // 5. Robust JSON Extraction
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                Log::error("Gemini Invalid Structure: " . json_encode($data));
                return response()->json(['error' => 'Invalid response structure from AI'], 500);
            }

            $rawText = $data['candidates'][0]['content']['parts'][0]['text'];

            // Clean Markdown (```json ... ```)
            $cleanJson = preg_replace('/^```json\s*|\s*```$/', '', trim($rawText));
            $cleanJson = strip_tags($cleanJson); // Remove any stray HTML tags

            // Validate JSON
            $questions = json_decode($cleanJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("JSON Decode Error: " . json_last_error_msg() . " | Raw: " . $cleanJson);
                // Fallback: Try to clean common trailing commas or errors
                return response()->json(['error' => 'AI generated invalid JSON. Try again.'], 500);
            }
            
            return response()->json($questions);

        } catch (\Exception $e) {
            Log::error("Critical Controller Error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}