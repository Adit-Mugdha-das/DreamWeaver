<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Dream;
use App\Helpers\GeminiHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
            'used_types' => 'nullable|array'
        ]);

        $usedTypes = $data['used_types'] ?? [];

        $emotion = null;
        $short = null;

        if (in_array('emotion', $usedTypes)) {
            $emotion = GeminiHelper::analyzeEmotion($data['content']);
        }

        if (in_array('short', $usedTypes)) {
            $short = GeminiHelper::analyzeShort($data['content']);
        }

        // ✅ Log the current user ID before saving
        Log::info('Current Auth ID for dream save:', ['user_id' => Auth::id()]);

        $dream = Dream::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'emotion_summary' => $emotion,
            'short_interpretation' => $short,
            'user_id' => Auth::id(), // ✅ Assign current user
        ]);

        return response()->json(['status' => 'success', 'dream' => $dream]);
    }

    // Handle normal POST (non-JS) request
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    Dream::create([
        'title' => $validated['title'],
        'content' => $validated['content'],
        'emotion_summary' => GeminiHelper::analyzeEmotion($validated['content']),
        'short_interpretation' => GeminiHelper::analyzeShort($validated['content']),
        'user_id' => Auth::id(), // ✅ Assign current user
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

    // ✅ New method to delete a dream
    public function destroy(Dream $dream)
{
    $dream->delete();

    if (request()->expectsJson()) {
        return response()->json(['status' => 'success']);
    }

    return redirect()->route('dreams.index')->with('success', 'Dream deleted successfully.');
}

    public function showDashboard()
{
    // Get all dreams for the logged-in user
    $dreams = Dream::where('user_id', Auth::id())->get();

    // Emotion Count for Pie Chart
    $emotionCounts = $dreams->groupBy('emotion_summary')->map->count();

    // Dreams Per Week for Line Chart
    $weeklyCounts = $dreams->groupBy(function ($dream) {
        return Carbon::parse($dream->created_at)->startOfWeek()->format('Y-m-d');
    })->map->count();

    // Keyword Extraction (Simple NLP)
    $keywords = collect();
    foreach ($dreams as $dream) {
        $words = str_word_count(strtolower(strip_tags($dream->content)), 1);
        $filtered = array_filter($words, fn($w) => strlen($w) > 3 && !in_array($w, ['this', 'that', 'with', 'have', 'just']));
        $keywords = $keywords->merge($filtered);
    }
    $topKeywords = $keywords->countBy()->sortDesc()->take(10);

    // Pass to view
    return view('dreams.dashboard', compact('emotionCounts', 'weeklyCounts', 'topKeywords'));
}



}

