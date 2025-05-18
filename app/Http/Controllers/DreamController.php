<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Dream;
use App\Helpers\GeminiHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DreamController extends Controller
{
    public function create()
    {
        return view('dreams.create');
    }

    public function store(Request $request)
    {
        if ($request->expectsJson()) {
            // ✅ Handle AJAX request and use already-generated interpretations
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'used_types' => 'nullable|array',
                'short_interpretation' => 'nullable|string',
                'emotion_summary' => 'nullable|string',
            ]);

            $usedTypes = $data['used_types'] ?? [];

            $emotion = $data['emotion_summary'] ?? null;
            $short = $data['short_interpretation'] ?? null;

            Log::info('Current Auth ID for dream save:', ['user_id' => Auth::id()]);

            $dream = Dream::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'emotion_summary' => $emotion,
                'short_interpretation' => $short,
                'user_id' => Auth::id(),
            ]);

            return response()->json(['status' => 'success', 'dream' => $dream]);
        }

        // ✅ Handle non-AJAX fallback
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Dream::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'emotion_summary' => GeminiHelper::analyzeEmotion($validated['content']),
            'short_interpretation' => GeminiHelper::analyzeShort($validated['content']),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('dreams.index')->with('success', 'Dream saved with emotion and short interpretation!');
    }

    public function index()
    {
        $dreams = Dream::latest()->get();
        return view('dreams.index', compact('dreams'));
    }

    public function interpret(Request $request)
    {
        try {
            $data = $request->json()->all();

            if (!isset($data['title'], $data['content'], $data['type'])) {
                return response()->json(['error' => 'Missing fields.'], 422);
            }

            $type = $data['type'];
            $result = null;

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
        $dreams = Dream::where('user_id', Auth::id())->get();

        $emotionCounts = $dreams->groupBy('emotion_summary')->map->count();

        $weeklyCounts = $dreams->groupBy(function ($dream) {
            return Carbon::parse($dream->created_at)->startOfWeek()->format('Y-m-d');
        })->map->count();

        $keywords = collect();
        foreach ($dreams as $dream) {
            $words = str_word_count(strtolower(strip_tags($dream->content)), 1);
            $filtered = array_filter($words, fn($w) => strlen($w) > 3 && !in_array($w, ['this', 'that', 'with', 'have', 'just']));
            $keywords = $keywords->merge($filtered);
        }
        $topKeywords = $keywords->countBy()->sortDesc()->take(10);

        return view('dreams.dashboard', compact('emotionCounts', 'weeklyCounts', 'topKeywords'));
    }

    public function exportPdf()
    {
        $user = Auth::user();
        $dreams = $user->dreams;

        $pdf = Pdf::loadView('dreams.export_pdf', compact('dreams', 'user'));
        return $pdf->download('my_dreams.pdf');
    }

    public function downloadSingle(Dream $dream)
    {
        $user = Auth::user();

        if ($dream->user_id !== $user->id) {
            abort(403);
        }

        $pdf = Pdf::loadView('dreams.single_pdf', compact('dream', 'user'));
        return $pdf->download($dream->title . '.pdf');
    }
}
