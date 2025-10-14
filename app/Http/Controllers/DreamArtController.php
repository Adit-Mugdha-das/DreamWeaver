<?php

namespace App\Http\Controllers;

use App\Models\Dream;
use App\Models\DreamArt;
use App\Helpers\GeminiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // Delete image file if exists
        if ($art->image_path && Storage::disk('public')->exists($art->image_path)) {
            Storage::disk('public')->delete($art->image_path);
        }

        $art->delete();

        return back()->with('success', 'Dream art deleted successfully!');
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
