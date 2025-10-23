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
        $emotion   = $data['emotion_summary'] ?? null;
        $short     = $data['short_interpretation'] ?? null;

        // Store both original emotion and normalized category
        $emotionCategory = null;
        if ($emotion) {
            // 🔧 Normalize to get the category for avatar/totem mapping
            $emotionCategory = $this->normalizeEmotion($emotion);
            session(['last_emotion' => $emotionCategory]);

            // ✅ Map emotion CATEGORY to token and save
            $token = match($emotionCategory) {
                // existing
                'joy'                    => 'wings',
                'fear'                   => 'mask',
                'calm'                   => 'cloud',
                'confusion', 'confused'  => 'swirl',
                'anger'                  => 'fire',

                // new
                'sadness'                => 'tear',
                'awe'                    => 'star',
                'love'                   => 'heart',
                'curiosity'              => 'compass',
                'gratitude'              => 'quill',
                'pride'                  => 'crest',
                'relief'                 => 'key',
                'nostalgia'              => 'moon',
                'surprise'               => 'bolt',
                'hope'                   => 'leaf',
                'courage'                => 'shield',
                'trust'                  => 'anchor',

                default                  => 'mirror',
            };

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $currentTokens   = $user->dream_tokens ?? [];
            $currentTokens[] = $token;
            $user->dream_tokens = array_values(array_unique($currentTokens));
            $user->save();
        }

        Log::info('Current Auth ID for dream save:', ['user_id' => Auth::id()]);

        $dream = Dream::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'emotion_summary' => $emotion,        // Original detailed emotion (despair, hopeless, etc)
            'emotion_category' => $emotionCategory, // Normalized category (sadness, fear, joy, etc)
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
    $short   = GeminiHelper::analyzeShort($validated['content']);

    // 🔧 Normalize BEFORE storing / mapping
    $emotion = $this->normalizeEmotion($emotion);
    session(['last_emotion' => $emotion]);

    // ✅ Map emotion to token and save
    $token = match($emotion) {
        // existing
        'joy'                    => 'wings',
        'fear'                   => 'mask',
        'calm'                   => 'cloud',
        'confusion', 'confused'  => 'swirl',
        'anger'                  => 'fire',

        // new
        'sadness'                => 'tear',
        'awe'                    => 'star',
        'love'                   => 'heart',
        'curiosity'              => 'compass',
        'gratitude'              => 'quill',
        'pride'                  => 'crest',
        'relief'                 => 'key',
        'nostalgia'              => 'moon',
        'surprise'               => 'bolt',
        'hope'                   => 'leaf',
        'courage'                => 'shield',
        'trust'                  => 'anchor',

        default                  => 'mirror',
    };

    /** @var \App\Models\User $user */
    $user = Auth::user();
    $currentTokens   = $user->dream_tokens ?? [];
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

        // Calculate emotion category if emotion exists
        $emotionCategory = null;
        if (!empty($data['emotion_summary'])) {
            $emotionCategory = $this->normalizeEmotion($data['emotion_summary']);
        }

        $dream = Dream::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'emotion_summary' => $data['emotion_summary'] ?? null,  // Original emotion
            'emotion_category' => $emotionCategory,                 // Normalized category
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
                // ✅ Return the ORIGINAL emotion for display (rich and varied)
                // We'll normalize it when saving, not here
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

    /**
     * Delete a totem from user's collection
     */
    public function deleteTotem(Request $request, $token)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            // Get current tokens
            $currentTokens = $user->dream_tokens ?? [];
            
            // Remove the specified token
            $updatedTokens = array_values(array_filter($currentTokens, function($t) use ($token) {
                return strtolower($t) !== strtolower($token);
            }));
            
            // Save updated tokens
            $user->dream_tokens = $updatedTokens;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Totem deleted successfully'
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Totem deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete totem'
            ], 500);
        }
    }

    
public function showDashboard()
{
    $dreams = Dream::where('user_id', Auth::id())->get();

    // Normalize and group emotions by category
    $emotionCounts = $dreams->groupBy(function ($dream) {
        // Use emotion_category for grouping, fallback to emotion_summary for old dreams
        $emotion = strtolower(trim($dream->emotion_category ?? $dream->emotion_summary ?? 'unknown'));
        return Str::title($emotion); // e.g., sadness, fear → Sadness, Fear
    })->map->count();

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
        // Use emotion_category for matching (normalized emotion)
        $category = $dream->emotion_category ?? $dream->emotion_summary;
        return strtolower($category) === strtolower($emotion);
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

public function like($id)
{
    $user = Auth::user();
    $dream = Dream::findOrFail($id);

    $like = $dream->likes()->where('user_id', $user->id)->first();

    if ($like) {
        $like->delete(); // Unlike
    } else {
        $dream->likes()->create(['user_id' => $user->id]);

        // 🔔 Trigger notification if the liker is not the owner
        if ($dream->user_id !== $user->id) {
            $dream->user->notify(new \App\Notifications\DreamLiked($user, $dream));
        }
    }

    return response()->json(['likes' => $dream->likes()->count()]);
}



public function comment(Request $request, Dream $dream)
{
    $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    $comment = new Comment();
    $comment->user_id = Auth::id();
    $comment->dream_id = $dream->id;
  $comment->setAttribute('content', $request->string('content')); 
    $comment->save();

    // 🔔 Trigger notification if the commenter is not the dream owner
    if ($dream->user_id !== Auth::id()) {
        $dream->user->notify(new \App\Notifications\DreamCommented(Auth::user(), $dream, $comment));
    }

    return response()->json([
        'user' => Auth::user()->name,
        'content' => $comment->content,
        'time' => $comment->created_at->diffForHumans(),
    ]);
}

public function getLikes($id)
{
    $dream = Dream::with('likes.user')->findOrFail($id);
    $users = $dream->likes->pluck('user.name');
    return response()->json(['users' => $users]);
}

public function shareLater(Request $request, Dream $dream)
{
    $user = Auth::user();

    if (!$user || $dream->user_id !== $user->id) {
        return abort(403);
    }

    $dream->is_shared = true;
    $dream->save();

    return response()->json(['message' => 'Dream shared successfully.']);
}


// Add inside DreamController (class scope)
/**
 * Collapse synonyms/typos to a canonical emotion.
 * e.g., "horror" → "fear", "ecstatic" → "joy"
 */
private function normalizeEmotion(string $raw): string
{
    $e = strtolower(trim($raw));
    if ($e === '') return 'neutral';

    // Canonical → synonyms
    $syn = [
        'joy' => [
            'happy','delight','glad','ecstatic','excited','cheerful','elated',
            'pleased','content','blissful','joyful','merry','gleeful'
        ],
        'fear' => [
            'horror','terror','terrified','fright','frightened','dread','scared',
            'scary','panic','panicked','petrified','afraid','spooked','creepy',
            'creeped out','fearful','nervous','anxious'
        ],
        'sadness' => [
            'sad','sorrow','blue','depressed','melancholy','mournful','down',
            'grief','heartbroken','lonely','tearful','despair','hopeless'
        ],
        'calm' => [
            'peaceful','serene','relaxed','composed','tranquil','soothing',
            'quiet','still','untroubled','balanced'
        ],
        'anger' => [
            'mad','furious','rage','irritated','annoyed','enraged','outraged',
            'resentful','hostile','infuriated','angry','frustrated'
        ],
        'confusion' => [
            'confused','puzzled','uncertain','doubtful','bewildered','perplexed',
            'lost','disoriented','hesitant','unclear'
        ],
        'awe' => [
            'wonder','amazement','astonishment','admiration','reverence','marvel','wow'
        ],
        'love' => [
            'affection','fondness','devotion','adoration','passion','caring',
            'romance','liking','attachment'
        ],
        'curiosity' => [
            'interested','inquisitive','exploring','investigative','questioning',
            'wondering','nosy','seeking'
        ],
        'gratitude' => [
            'thankful','appreciative','grateful','obliged','indebted',
            'acknowledging','recognition'
        ],
        'pride' => [
            'proud','satisfied','dignity','self-esteem','honor','confidence','selfrespect'
        ],
        'relief' => [
            'comfort','ease','assurance','reassured','release','freedom','unburdened'
        ],
        'nostalgia' => [
            'homesick','yearning','reminiscent','sentimental','longing',
            'wistful','memories'
        ],
        'surprise' => [
            'shocked','astonished','startled','amazed','stunned','flabbergasted','unexpected'
        ],
        'hope' => [
            'optimism','faith','expectation','trusting','confident','aspiration','positive'
        ],
        'courage' => [
            'bravery','boldness','fearless','valiant','heroic','guts','determined','dauntless'
        ],
        'trust' => [
            'belief','confidence','faith','dependable','secure','assured','reliable'
        ],
    ];

    // Exact canonical hit
    if (array_key_exists($e, $syn)) return $e;

    // Direct synonym hit
    foreach ($syn as $canon => $aliases) {
        if (in_array($e, $aliases, true)) return $canon;
    }

    // Light fuzzy fallback for minor typos (e.g. "horor")
    $candidates = array_merge(array_keys($syn), ...array_values($syn));
    $best = null; $bestPct = 0.0;
    foreach ($candidates as $cand) {
        similar_text($e, $cand, $pct);
        if ($pct > $bestPct) { $bestPct = $pct; $best = $cand; }
    }
    if ($best && $bestPct >= 80) {
        if (array_key_exists($best, $syn)) return $best;
        foreach ($syn as $canon => $aliases) {
            if (in_array($best, $aliases, true)) return $canon;
        }
    }

    return $e; // leave unknowns as-is
}
// app/Http/Controllers/DreamController.php
public function similarByEmotion(\Illuminate\Http\Request $request)
{
    $emotion = trim((string) $request->query('emotion', ''));
    abort_unless($emotion !== '', 400, 'emotion is required');

    $norm = \Illuminate\Support\Str::lower($emotion);

    $dreams = \App\Models\Dream::query()
        ->where('user_id', \Illuminate\Support\Facades\Auth::id())
        ->where(function ($q) use ($norm) {
            // Match by emotion_category (normalized) for consistent grouping
            $q->whereRaw('LOWER(COALESCE(emotion_category, emotion_summary, "")) = ?', [$norm]);
        })
        ->latest()
        ->take(30)
        // Include content so we can show the original message
        ->get(['id', 'title', 'created_at', 'emotion_summary', 'content']);

    return view('dreams.partials.similar_by_emotion', [
        'emotion' => $norm,
        'dreams'  => $dreams,
    ]);
}


}
