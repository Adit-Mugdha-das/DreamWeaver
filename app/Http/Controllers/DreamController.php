<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Dream;
use App\Helpers\GeminiHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Models\Like;
use App\Models\Comment;


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
            'story_generation' => 'nullable|string',
            'long_narrative' => 'nullable|string',
        ]);

        $usedTypes = $data['used_types'] ?? [];
        $emotion = $data['emotion_summary'] ?? null;
        $short = $data['short_interpretation'] ?? null;

        if ($emotion) {
            session(['last_emotion' => $emotion]);
            $emotion = strtolower($emotion);

            // ✅ Map emotion to token and save
            $token = match($emotion) {
                'joy' => 'wings',
                'fear' => 'mask',
                'calm' => 'cloud',
                'confused' => 'swirl',
                'anger' => 'fire',
                default => 'mirror',
            };

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $currentTokens = $user->dream_tokens ?? [];
            $currentTokens[] = $token;
            $user->dream_tokens = array_values(array_unique($currentTokens));
            $user->save();
        }

        Log::info('Current Auth ID for dream save:', ['user_id' => Auth::id()]);

        $dream = Dream::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'emotion_summary' => $emotion,
            'short_interpretation' => $short,
            'story_generation' => $data['story_generation'] ?? null,
            'long_narrative' => $data['long_narrative'] ?? null,
            'is_shared' => false, // ✅ Always private
            'user_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success', 'dream' => $dream]);
    }

    // ✅ Handle non-AJAX fallback
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    $emotion = GeminiHelper::analyzeEmotion($validated['content']);
    $short = GeminiHelper::analyzeShort($validated['content']);

    session(['last_emotion' => $emotion]);
    $emotion = strtolower($emotion);

    // ✅ Map emotion to token and save
    $token = match($emotion) {
        'joy' => 'wings',
        'fear' => 'mask',
        'calm' => 'cloud',
        'confused' => 'swirl',
        'anger' => 'fire',
        default => 'mirror',
    };

    /** @var \App\Models\User $user */
    $user = Auth::user();
    $currentTokens = $user->dream_tokens ?? [];
    $currentTokens[] = $token;
    $user->dream_tokens = array_values(array_unique($currentTokens));
    $user->save();

    Dream::create([
        'title' => $validated['title'],
        'content' => $validated['content'],
        'emotion_summary' => $emotion,
        'short_interpretation' => $short,
        'is_shared' => false, // ✅ Always private
        'user_id' => Auth::id(),
    ]);

    return redirect()->route('dreams.index')->with('success', 'Dream saved with emotion and short interpretation!');
}


    public function share(Request $request)
    {
        // Save & Mark as Shared
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'emotion_summary' => 'nullable|string',
            'short_interpretation' => 'nullable|string',
            'story_generation' => 'nullable|string',
            'long_narrative' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $dream = Dream::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'emotion_summary' => $data['emotion_summary'] ?? null,
            'short_interpretation' => $data['short_interpretation'] ?? null,
            'story_generation' => $data['story_generation'] ?? null,
            'long_narrative' => $data['long_narrative'] ?? null,
            'is_shared' => true,
            'user_id' => $user->id,
        ]);

        return response()->json(['status' => 'shared', 'dream' => $dream]);
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

        switch ($type) {
            case 'emotion':
                $result = GeminiHelper::analyzeEmotion($data['content']);
                break;
            case 'short':
                $result = GeminiHelper::analyzeShort($data['content']);
                break;
            case 'story':
                $result = GeminiHelper::generateStory($data['content']);
                break;
            case 'long':
                $result = GeminiHelper::generateNarrative($data['content']);
                break;
            default:
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

        // Group dreams by exact day
        $dailyCounts = $dreams->groupBy(function ($dream) {
            return Carbon::parse($dream->created_at)->format('Y-m-d');
        })->map->count();

        // Extract keywords
        $keywords = collect();
        foreach ($dreams as $dream) {
            $words = str_word_count(strtolower(strip_tags($dream->content)), 1);
            $filtered = array_filter($words, fn($w) => strlen($w) > 3 && !in_array($w, ['this', 'that', 'with', 'have', 'just']));
            $keywords = $keywords->merge($filtered);
        }
        $topKeywords = $keywords->countBy()->sortDesc()->take(10);

        return view('dreams.dashboard', compact('emotionCounts', 'dailyCounts', 'topKeywords'));
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

public function showDreamMap()
{
    $user = Auth::user();

    // If tokens are already stored as array (casted in User model)
    $tokens = $user->dream_tokens ?? [];

    // Realm unlock logic based on totems
    $unlockedViaTotem = [
    'forest' => in_array('mask', $tokens),   // match stored 'mask'
    'sky'    => in_array('wings', $tokens),  // match stored 'wings'
    'cloud'  => in_array('cloud', $tokens),  // match stored 'cloud'
];


    return view('dreams.dream_map', compact('unlockedViaTotem'));
}

public function getByEmotion($emotion)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $matchedDreams = $user->dreams->filter(function ($dream) use ($emotion) {
        return strtolower($dream->emotion_summary) === strtolower($emotion);
    });

    if ($matchedDreams->isEmpty()) {
        return response()->json(['html' => '<p class="text-gray-400 italic mt-4">No dreams found with this emotion.</p>']);
    }

    $html = '<ul class="text-left space-y-2 mt-4">';
    foreach ($matchedDreams as $dream) {
        $html .= '<li class="border-b border-purple-500 pb-2">' . e(Str::limit($dream->content, 120)) . '</li>';
    }
    $html .= '</ul>';

    return response()->json(['html' => $html]);
}

public function showSkyTemple()
{
    return view('dreams.sky');
}

public function showForestEntrance()
{
    return view('dreams.forest'); // assuming your Blade file is forest.blade.php
}
public function showCloudEntrance()
{
    return view('dreams.cloud');
}

public function sharedDreams()
{
    $dreams = Dream::where('is_shared', true)->with('user')->latest()->get();
    return view('dreams.shared', compact('dreams'));
}

public function like($id) {
    $user = Auth::user();
    $dream = Dream::findOrFail($id);

    $like = $dream->likes()->where('user_id', $user->id)->first();

    if ($like) {
        $like->delete(); // Unlike
    } else {
        $dream->likes()->create(['user_id' => $user->id]);
    }

    return response()->json(['likes' => $dream->likes()->count()]);
}

public function comment(Request $request, $id) {
    $request->validate(['content' => 'required|string|max:1000']);
    $dream = Dream::findOrFail($id);

    $comment = $dream->comments()->create([
        'user_id' => Auth::id(),
        'content' => $request->content
    ]);

    return response()->json([
        'user' => $comment->user->name,
        'content' => $comment->content,
        'time' => $comment->created_at->diffForHumans()
    ]);
}

}
