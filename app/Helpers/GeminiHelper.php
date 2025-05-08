<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class GeminiHelper
{
    public static function analyzeEmotion($text)
    {
        $apiKey = env('GEMINI_API_KEY');

        $client = new Client();
        $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=$apiKey", [
            'json' => [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "Analyze the emotional tone of this dream and return only a single-word emotion like fear, joy, sadness, etc.: \n\n$text"]
                        ]
                    ]
                ]
            ]
        ]);

        $body = $response->getBody()->getContents();
        Log::info('Gemini emotion response', ['body' => $body]);

        $data = json_decode($body, true);
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No emotion detected';
    }

    public static function analyzeShort($text)
    {
        $apiKey = env('GEMINI_API_KEY');

        $prompt = "Give a short (1-3 sentence) interpretation of this dream. Make it meaningful, clear, and easy to understand:\n\n$text";

        $client = new Client();
        $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=$apiKey", [
            'json' => [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]
        ]);

        $body = $response->getBody()->getContents();
        Log::info('Gemini short interpretation response', ['body' => $body]);

        $data = json_decode($body, true);
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No interpretation available';
    }
}
