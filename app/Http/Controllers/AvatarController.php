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
        $emotion = strtolower(trim($rawEmotion));

        // Map emotion to visual avatar config
        $avatar = match ($emotion) {
            'joy' => ['color' => 'gold', 'item' => 'wings'],
            'fear' => ['color' => 'black', 'item' => 'mask'],
            'calm' => ['color' => 'blue', 'item' => 'cloud'],
            'confused' => ['color' => 'gray', 'item' => 'swirl'],
            'anger' => ['color' => 'red', 'item' => 'fire'],
            default => ['color' => 'gray', 'item' => 'mirror'],
        };

        /** @var User $user */
        $user = Auth::user();
        $user->avatar_config = $avatar;
        $user->save();

        Avatar::create([
            'user_id' => $user->id,
            'color' => $avatar['color'],
            'item' => $avatar['item'],
        ]);

        return redirect()->route('avatar.show')
                         ->with('message', 'Avatar generated based on your last dream emotion.');
    }
}
