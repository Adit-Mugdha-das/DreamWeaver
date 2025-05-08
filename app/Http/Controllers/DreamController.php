<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    // ✅ API endpoint for emotion and short interpretation
    public function interpret(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string'
        ]);

        if ($validated['type'] === 'emotion') {
            $emotion = GeminiHelper::analyzeEmotion($validated['content']);
            return response()->json(['result' => $emotion]);
        }

        if ($validated['type'] === 'short') {
            $short = GeminiHelper::analyzeShort($validated['content']);
            return response()->json(['result' => $short]);
        }

        return response()->json(['result' => 'Interpretation type not supported.']);
    }
}
