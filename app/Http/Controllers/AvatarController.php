<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Avatar;
use App\Models\User;

class AvatarController extends Controller
{
    /**
     * Show the user's dream avatar and saved avatars.
     */
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();

        // Eager load avatars relationship
        $user->load('avatars');

        $savedAvatars = $user->avatars->sortByDesc('created_at')->take(12);

        return view('dreams.avatar', [
            'avatar' => $user->avatar_config,
            'savedAvatars' => $savedAvatars,
        ]);
    }

    /**
     * Generate avatar from last detected dream emotion.
     */
    public function generate()
    {
        $rawEmotion = session('last_emotion') ?? 'neutral';

        // 🔧 Normalize BEFORE mapping so synonyms like "horror" → "fear"
        $emotion = $this->normalizeEmotion($rawEmotion);

        // 🔁 emotion → avatar item + color mapping
        $avatar = match ($emotion) {
            'joy'        => ['color' => 'gold',   'item' => 'wings'],
            'fear'       => ['color' => 'black',  'item' => 'mask'],
            'calm'       => ['color' => 'blue',   'item' => 'cloud'],
            'confused',
            'confusion'  => ['color' => 'gray',   'item' => 'swirl'],
            'anger'      => ['color' => 'red',    'item' => 'fire'],

            // 🆕 New emotions and their avatars
            'sadness'    => ['color' => 'navy',    'item' => 'tear'],
            'awe'        => ['color' => 'indigo',  'item' => 'star'],
            'love'       => ['color' => 'pink',    'item' => 'heart'],
            'curiosity'  => ['color' => 'teal',    'item' => 'compass'],
            'gratitude'  => ['color' => 'amber',   'item' => 'quill'],
            'pride'      => ['color' => 'purple',  'item' => 'crest'],
            'relief'     => ['color' => 'green',   'item' => 'key'],
            'nostalgia'  => ['color' => 'silver',  'item' => 'moon'],
            'surprise'   => ['color' => 'yellow',  'item' => 'bolt'],
            'hope'       => ['color' => 'lime',    'item' => 'leaf'],
            'courage'    => ['color' => 'maroon',  'item' => 'shield'],
            'trust'      => ['color' => 'cyan',    'item' => 'anchor'],

            // Default fallback
            default      => ['color' => 'gray',    'item' => 'mirror'],
        };

        /** @var User $user */
        $user = Auth::user();
        $user->avatar_config = $avatar;
        $user->save();

        Avatar::create([
            'user_id' => $user->id,
            'color'   => $avatar['color'],
            'item'    => $avatar['item'],
        ]);

        return redirect('/test-avatar')
            ->with('message', 'Avatar generated based on your last dream emotion.');
    }

    // Add inside AvatarController (class scope)
    private function normalizeEmotion(string $raw): string
    {
        $e = strtolower(trim($raw));
        if ($e === '') return 'neutral';

        $syn = [
            'fear' => [
                'horror','terror','terrified','fright','frightened','dread',
                'scared','scary','panic','panicked','petrified','afraid',
                'spooked','creepy','creeped out'
            ],
        ];

        // Exact canonical
        if (array_key_exists($e, $syn)) return $e;

        // Direct synonym
        foreach ($syn as $canon => $aliases) {
            if (in_array($e, $aliases, true)) return $canon;
        }

        // Light fuzzy matching (helps with typos like "horor")
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

        return $e;
    }
}
