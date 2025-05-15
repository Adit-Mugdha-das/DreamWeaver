<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Dream;
use App\Helpers\GeminiHelper;

class DreamController extends Controller
{
    public function create()
    {
        return view('dreams.create');
    }

    public function store(Request $request)
    {
        if ($request->expectsJson()) {
            // Handle AJAX request
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            // Analyze both emotion and short interpretation
            $emotion = GeminiHelper::analyzeEmotion($data['content']);
            $short = GeminiHelper::analyzeShort($data['content']);

            $dream = Dream::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'emotion_summary' => $emotion,
                'short_interpretation' => $short,
            ]);

            return response()->json(['status' => 'success', 'dream' => $dream]);
        }

        // Handle normal POST request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Analyze both emotion and short interpretation
        $emotion = GeminiHelper::analyzeEmotion($validated['content']);
        $short = GeminiHelper::analyzeShort($validated['content']);

        Dream::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'emotion_summary' => $emotion,
            'short_interpretation' => $short,
        ]);

        return redirect()->route('dreams.index')->with('success', 'Dream saved with emotion and short interpretation!');
    }

    public function index()
    {
        $dreams = Dream::latest()->get();
        return view('dreams.index', compact('dreams'));
    }

    // ✅ Fixed JSON-safe dream interpretation
    public function interpret(Request $request)
    {
        try {
            $data = $request->json()->all(); // Get JSON payload

            if (!isset($data['title'], $data['content'], $data['type'])) {
                return response()->json(['error' => 'Missing fields.'], 422);
            }

            $type = $data['type'];

            if ($type === 'emotion') {
                $result = GeminiHelper::analyzeEmotion($data['content']);
            } elseif ($type === 'short') {
                $result = GeminiHelper::analyzeShort($data['content']);
            } else {
                return response()->json(['error' => 'Invalid interpretation type.'], 400);
            }

            return response()->json(['result' => $result]);

        } catch (\Throwable $e) {
            Log::error('Interpretation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to interpret dream.'], 500);
        }
    }
}
