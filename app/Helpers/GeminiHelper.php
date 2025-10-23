<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class GeminiHelper
{
    /** Build endpoint from .env, defaulting to v1 + gemini-2.5-flash */
    private static function endpoint(): string
    {
        $version = env('GEMINI_API_VERSION', 'v1');
        $model   = env('GEMINI_MODEL', 'gemini-2.5-flash');

        // Remove brittle aliases like "-latest" / "-experimental"
        $model = preg_replace('/-(latest|experimental)$/', '', $model);

        return "https://generativelanguage.googleapis.com/{$version}/models/{$model}:generateContent";
    }

    /** Core POST call; header auth; error-aware; logs useful info */
    private static function callApi(string $prompt): array
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            throw new \RuntimeException('GEMINI_API_KEY is missing.');
        }

        $client  = new Client(['timeout' => 40]);
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        $endpoint = self::endpoint();

        $response = $client->post($endpoint, [
            'headers' => [
                'Content-Type'   => 'application/json',
                'x-goog-api-key' => $apiKey,   // ← header auth (no ?key=)
            ],
            'json'         => $payload,
            'http_errors'  => false,          // don't throw; we check status manually
        ]);

        $status = $response->getStatusCode();
        $body   = (string) $response->getBody();

        // keep your style of logging (plus endpoint/status for easier debugging)
        Log::info('Gemini raw response', [
            'endpoint' => $endpoint,
            'status'   => $status,
            'sample'   => mb_substr($body, 0, 800),
        ]);

        $data = json_decode($body, true);

        if ($status >= 400) {
            $msg = $data['error']['message'] ?? "Gemini request failed (HTTP {$status})";
            Log::error('Gemini API error', ['status' => $status, 'message' => $msg, 'endpoint' => $endpoint]);
            throw new \RuntimeException($msg);
        }

        return $data ?? [];
    }

    public static function analyzeEmotion($text)
    {
        $prompt = "Analyze the emotional tone of this dream and return only a single-word emotion. Choose the MOST SPECIFIC and ACCURATE emotion from this list:

Joy family: joy, happy, delight, ecstatic, excited, cheerful, elated, pleased, content, blissful, merry, gleeful
Fear family: fear, horror, terror, terrified, fright, frightened, dread, scared, panic, panicked, petrified, afraid, spooked, creepy, nervous, anxious
Sadness family: sadness, sad, sorrow, depressed, melancholy, mournful, grief, heartbroken, lonely, tearful, despair, hopeless
Calm family: calm, peaceful, serene, relaxed, composed, tranquil, soothing, quiet, still, balanced
Anger family: anger, mad, furious, rage, irritated, annoyed, enraged, outraged, resentful, hostile, infuriated, frustrated
Confusion family: confusion, confused, puzzled, uncertain, doubtful, bewildered, perplexed, lost, disoriented, hesitant
Awe family: awe, wonder, amazement, astonishment, admiration, reverence, marvel
Love family: love, affection, fondness, devotion, adoration, passion, caring, romance
Curiosity family: curiosity, interested, inquisitive, exploring, investigative, questioning, wondering, seeking
Gratitude family: gratitude, thankful, appreciative, grateful, obliged
Pride family: pride, proud, satisfied, dignity, honor, confidence
Relief family: relief, comfort, ease, assurance, reassured, release, freedom
Nostalgia family: nostalgia, homesick, yearning, reminiscent, sentimental, longing, wistful
Surprise family: surprise, shocked, astonished, startled, amazed, stunned, flabbergasted, unexpected
Hope family: hope, optimism, faith, expectation, trusting, aspiration, positive
Courage family: courage, bravery, boldness, fearless, valiant, heroic, determined, dauntless
Trust family: trust, belief, confidence, dependable, secure, assured, reliable

Return ONLY the single most fitting emotion word from the list above. Dream text:

{$text}";

        $data = self::callApi($prompt);

        // keep your previous behavior / log label
        $out = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No emotion detected';
        Log::info('Gemini emotion response', ['out' => $out]);

        return trim($out);
    }

    public static function analyzeShort($text)
    {
        $prompt = "Give a short (1-3 sentence) interpretation of this dream. Make it meaningful, clear, and easy to understand:\n\n{$text}";

        $data = self::callApi($prompt);

        $out = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No interpretation available';
        Log::info('Gemini short interpretation response', ['out' => $out]);

        return $out;
    }

    public static function generateStory($text)
    {
        $prompt = "Turn this dream into a creative short story, imaginative and emotionally engaging. Do not exceed 120 words:\n\n{$text}";

        $data = self::callApi($prompt);

        $out = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No story generated';
        Log::info('Gemini story generation response', ['out' => $out]);

        return $out;
    }

    public static function generateNarrative($text)
    {
        $prompt = "Write a thoughtful and professional long narrative interpretation of the dream. Include possible meanings and emotional insights. Limit your response to a maximum of 150 words:\n\n{$text}";

        $data = self::callApi($prompt);

        $out = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No narrative generated';
        Log::info('Gemini long narrative response', ['out' => $out]);

        return $out;
    }

    public static function generateArtPrompt($text)
    {
        $prompt = "You are an AI art prompt generator. Based on this dream description, create a detailed, vivid, and artistic image generation prompt (for DALL-E or Midjourney style). Focus on visual imagery, colors, mood, symbolism, and surreal elements. Keep it under 300 characters.\n\nDream:\n{$text}\n\nArt Prompt:";

        $data = self::callApi($prompt);

        $out = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'A surreal dreamscape with ethereal colors and symbolic elements floating in space';
        Log::info('Gemini art prompt response', ['out' => $out]);

        return $out;
    }
}
