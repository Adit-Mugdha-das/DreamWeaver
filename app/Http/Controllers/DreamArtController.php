<?php

namespace App\Http\Controllers;

use App\Models\Dream;
use App\Models\DreamArt;
use App\Helpers\GeminiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class DreamArtController extends Controller
{
    /**
     * Show art gallery for a specific dream
     */
    public function show(Dream $dream)
    {
        // Ensure user owns this dream
        if ($dream->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $arts = DreamArt::where('dream_id', $dream->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('dreams.art.show', compact('dream', 'arts'));
    }

    /**
     * Generate an artistic prompt from dream content using Gemini API
     */
    public function generatePrompt(Dream $dream)
    {
        if ($dream->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        try {
            $dreamText = $this->prepareDreamText($dream);
            
            $geminiPrompt = "You are an AI art prompt generator. Based on this dream description, create a detailed, vivid, and artistic image generation prompt (for DALL-E or Midjourney style). Focus on visual imagery, colors, mood, symbolism, and surreal elements. Keep it under 300 characters.\n\nDream:\n{$dreamText}\n\nArt Prompt:";

            $prompt = GeminiHelper::generateArtPrompt($dreamText);

            return response()->json([
                'success' => true,
                'prompt' => $prompt,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate prompt: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate actual image using OpenAI DALL-E API
     */
    public function generateImage(Dream $dream, Request $request)
    {
        if ($dream->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Increase PHP execution time for this request
        set_time_limit(120);

        try {
            // Check if custom prompt is provided
            $customPrompt = $request->input('custom_prompt');
            
            if ($customPrompt && trim($customPrompt) !== '') {
                // Use custom prompt directly for DALL-E
                $prompt = trim($customPrompt);
            } else {
                // Generate prompt from dream using Gemini
                $dreamText = $this->prepareDreamText($dream);
                $prompt = GeminiHelper::generateArtPrompt($dreamText);
            }

            // Call OpenAI DALL-E API
            $apiKey = env('OPENAI_API_KEY');
            if (!$apiKey) {
                throw new \Exception('OpenAI API key not configured');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(90)->post('https://api.openai.com/v1/images/generations', [
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1024x1024',
                'quality' => 'standard',
                'response_format' => 'url',
            ]);

            if (!$response->successful()) {
                $errorBody = $response->body();
                Log::error('OpenAI API Error: ' . $errorBody);
                throw new \Exception('OpenAI API error: ' . $errorBody);
            }

            $data = $response->json();
            $imageUrl = $data['data'][0]['url'] ?? null;

            if (!$imageUrl) {
                throw new \Exception('No image URL returned from API');
            }

            // Download and save the image with timeout
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30
                ]
            ]);
            $imageContent = @file_get_contents($imageUrl, false, $context);
            
            if ($imageContent === false) {
                throw new \Exception('Failed to download image from OpenAI');
            }

            $filename = 'dream_art_' . time() . '_' . Str::random(10) . '.png';
            $path = 'dream_arts/' . $filename;
            Storage::disk('public')->put($path, $imageContent);

            // Save to database
            $art = DreamArt::create([
                'user_id' => Auth::id(),
                'dream_id' => $dream->id,
                'title' => $dream->title . ' - Generated Art',
                'prompt' => $prompt,
                'image_path' => $path,
                'style' => 'ai-generated',
                'description' => $customPrompt ? 'Custom prompt' : null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image generated successfully',
                'image_url' => asset('storage/' . $path),
                'prompt' => $prompt,
                'art_id' => $art->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Image generation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save a dream art entry (with generated prompt and optional manual image)
     */
    public function store(Request $request, Dream $dream)
    {
        if ($dream->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'prompt' => 'required|string',
            'style' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('dream_arts', 'public');
        }

        $art = DreamArt::create([
            'user_id' => Auth::id(),
            'dream_id' => $dream->id,
            'title' => $request->title,
            'prompt' => $request->prompt,
            'image_path' => $imagePath,
            'style' => $request->style ?? 'surreal',
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dream art saved successfully!',
            'art' => $art,
        ]);
    }

    /**
     * Delete a dream art
     */
    public function destroy(DreamArt $art)
    {
        if ($art->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        try {
            // Delete image file if exists
            if ($art->image_path && Storage::disk('public')->exists($art->image_path)) {
                Storage::disk('public')->delete($art->image_path);
            }

            $art->delete();

            // Return JSON for AJAX requests, redirect for form submissions
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dream art deleted successfully!'
                ]);
            }

            return back()->with('success', 'Dream art deleted successfully!');
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to delete artwork');
        }
    }

    /**
     * Prepare dream text for AI processing
     */
    private function prepareDreamText(Dream $dream): string
    {
        $parts = [];
        
        if ($dream->title) $parts[] = "Title: {$dream->title}";
        if ($dream->content) $parts[] = "Content: " . substr($dream->content, 0, 500);
        if ($dream->emotion_summary) $parts[] = "Emotion: {$dream->emotion_summary}";
        if ($dream->short_interpretation) $parts[] = "Meaning: " . substr($dream->short_interpretation, 0, 200);

        return implode("\n", $parts);
    }
}
