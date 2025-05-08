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
        Log::info('Gemini raw response', ['body' => $body]);

        $data = json_decode($body, true);
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No emotion detected';
    }
}
