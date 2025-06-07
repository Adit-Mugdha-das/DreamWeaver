<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\DreamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\AvatarController;
use Illuminate\Support\Str;
use App\Models\Dream;
use App\Http\Controllers\LibraryTextController;
use App\Http\Controllers\CommentController;

/**
 * 🧼 Always force logout and redirect to login when visiting "/"
 */
Route::get('/', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
});

/**
 * 🔐 Login & Register Routes
 */
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.submit');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

/**
 * ✅ Welcome page (after login)
 */
Route::get('/welcome', function () {
    return view('welcome');
})->middleware('auth')->name('welcome');

/**
 * 💤 Dream features - all require login
 */
Route::middleware('auth')->group(function () {

    // Dream routes
    Route::get('/dreams/create', [DreamController::class, 'create'])->name('dreams.create');
    Route::post('/dreams', [DreamController::class, 'store'])->name('dreams.store');
    Route::get('/dreams', [DreamController::class, 'index'])->name('dreams.index');
    Route::post('/dreams/interpret', [DreamController::class, 'interpret']);
    Route::get('/dreams/emotion/{emotion}', [DreamController::class, 'getByEmotion']);

    Route::delete('/dreams/{dream}', [DreamController::class, 'destroy'])->name('dreams.destroy');
    Route::get('/dashboard', [DreamController::class, 'showDashboard'])->name('dashboard');
    Route::get('/dreams/export/pdf', [DreamController::class, 'exportPdf'])->name('dreams.export.pdf');
    Route::get('/dreams/{dream}/download', [DreamController::class, 'downloadSingle'])->name('dreams.download');

    // Avatar routes
    Route::post('/avatar/generate', [AvatarController::class, 'generate'])->name('avatar.generate');

Route::get('/totems', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Read tokens directly from saved user field
    $tokens = $user->dream_tokens ?? [];

    $meanings = [
        'mirror' => ['🪞 Reflection & self-awareness', 'You looked into a mirror and saw your younger self.'],
        'wings'  => ['🪽 Freedom or ambition', 'You flew above mountains, free from all fears.'],
        'fire'   => ['🔥 Transformation or passion', 'You stood in a burning house but felt no pain.'],
        'mask'   => ['🎭 Hidden emotions or identity', 'You wore a mask in a crowded room and no one noticed.'],
        'cloud'  => ['☁️ Calm & clarity', 'You walked through mist and felt peace.'],
        'swirl'  => ['🌀 Confusion or mystery', 'You spun endlessly in a dream maze.'],
    ];

    // Get dream snippets for unlocked tokens
    $allDreams = $user->dreams;
    $dreamSnippets = [];
    foreach ($tokens as $token) {
        $match = $allDreams->first(function ($dream) use ($token, $meanings) {
            $emotionMap = [
                'wings' => 'joy',
                'mask' => 'fear',
                'cloud' => 'calm',
                'swirl' => 'confused',
                'fire' => 'anger',
                'mirror' => 'neutral',
            ];
            $expectedEmotion = $emotionMap[$token] ?? '';
            return strtolower($dream->emotion_summary) === $expectedEmotion;
        });

        $dreamSnippets[$token] = $match ? \Illuminate\Support\Str::limit($match->content, 120) : 'No related dream found.';
    }

    return view('dreams.totems', compact('tokens', 'meanings', 'dreamSnippets'));
})->name('totems');
  

    // Dream Map
    
    Route::get('/dream-map', [DreamController::class, 'showDreamMap'])->middleware('auth')->name('dream.map');



    // Portal page
    Route::get('/imagine', function () {
        return view('dreams.portal');
    })->name('imagine.portal');

    // Audio
    Route::get('/audio', function () {
        return view('dreams.audio');
    })->name('dreams.audio');

    // Support
    Route::get('/support', [SupportController::class, 'showNearbySupport'])->name('support');
    Route::post('/get-nearby', [SupportController::class, 'getNearby'])->name('support.nearby');
});

/**
 * ✉️ Forgot Password Flow using recovery Gmail
 */
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', [UserController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');
Route::post('/reset-password', [UserController::class, 'resetPassword'])->middleware('guest')->name('password.update');

Route::get('/test-avatar', function () {
    return app(AvatarController::class)->show();
});

Route::get('/dream-world/sky', [DreamController::class, 'showSkyTemple'])
    ->middleware('auth')
    ->name('sky.entrance');

Route::get('/dream-world/sky/inside', function () {
    return view('dreams.sky-inside');
})->middleware('auth')->name('sky.inside');
Route::get('/dream-world/sky/3d', function () {
    return view('dreams.sky-3d');
})->middleware('auth')->name('sky.3d');
Route::get('/dream-world/sky/final', function () {
    return view('dreams.sky-final');
})->middleware('auth')->name('sky.final');
Route::get('/dream-world/sky/epilogue', function () {
    return view('dreams.sky-epilogue');
})->middleware('auth')->name('sky.epilogue');
Route::get('/dream-world/sky/chapter6', function () {
    return view('dreams.sky-chapter6');
})->middleware('auth')->name('sky.chapter6');
Route::get('/dream-world/sky/awakening', function () {
    return view('dreams.sky-awakening');
})->middleware('auth')->name('sky.awakening');
Route::get('/dream-world/sky/transcendence', function () {
    return view('dreams.sky-transcendence');
})->middleware('auth')->name('sky.transcendence');
Route::get('/dream-world/sky/arrival', function () {
    return view('dreams.sky-arrival');
})->middleware('auth')->name('sky.arrival');
Route::get('/dream-world/sky/rebirth', function () {
    return view('dreams.sky-rebirth');
})->middleware('auth')->name('sky.rebirth');


Route::get('/dream-world/forest', [DreamController::class, 'showForestEntrance'])->middleware('auth')->name('forest.entrance');
Route::get('/dream-world/forest/inside', function () {
    return view('dreams.forest-inside');
})->middleware('auth')->name('forest.inside');

Route::get('/dream-world/forest/ending', function () {
    return view('dreams.forest-ending');
})->middleware('auth')->name('forest.ending');
Route::get('/dream-world/forest/3d', function () {
    return view('dreams.forest-3d');
})->middleware('auth')->name('forest.3d');
Route::get('/dream-world/forest/chapter4', function () {
    return view('dreams.forest4');
})->middleware('auth')->name('forest4');
Route::get('/dream-world/forest/5', function () {
    return view('dreams.forest5');
})->middleware('auth')->name('forest5');
Route::get('/dream-world/forest/6', function () {
    return view('dreams.forest6');
})->middleware('auth')->name('forest6');
Route::get('/dream-world/forest/7', function () {
    return view('dreams.forest7');
})->middleware('auth')->name('forest7');
Route::get('/dream-world/forest/8', function () {
    return view('dreams.forest8');
})->middleware('auth')->name('forest8');
Route::get('/dream-world/forest/9', function () {
    return view('dreams.forest9');
})->middleware('auth')->name('forest9');
Route::get('/dream-world/forest/10', function () {
    return view('dreams.forest10');
})->middleware('auth')->name('forest10');


Route::get('/dream-world/cloud', [DreamController::class, 'showCloudEntrance'])
    ->middleware('auth')
    ->name('cloud.entrance');
Route::get('/dream-world/cloud/2', function () {
    return view('dreams.cloud2');
})->middleware('auth')->name('cloud.2');
Route::get('/dream-world/cloud/3d', function () {
    return view('dreams.cloud-3d');
})->middleware('auth')->name('cloud.3d');
Route::get('/dream-world/cloud/4', function () {
    return view('dreams.cloud4');
})->middleware('auth')->name('cloud.4');
Route::get('/dream-world/cloud/5', function () {
    return view('dreams.cloud5');
})->middleware('auth')->name('cloud.5');
Route::get('/dream-world/cloud/6', function () {
    return view('dreams.cloud6');
})->middleware('auth')->name('cloud.6');
Route::get('/dream-world/cloud/7', function () {
    return view('dreams.cloud7');
})->middleware('auth')->name('cloud.7');
Route::get('/dream-world/cloud/8', function () {
    return view('dreams.cloud8');
})->middleware('auth')->name('cloud.8');
Route::get('/dream-world/cloud/9', function () {
    return view('dreams.cloud9');
})->middleware('auth')->name('cloud.9');
Route::get('/dream-world/cloud/10', function () {
    return view('dreams.cloud10');
})->middleware('auth')->name('cloud.10');

Route::get('/dream-library', [LibraryTextController::class, 'index'])->name('library.index');
Route::get('/dream-library/{id}', [LibraryTextController::class, 'show'])->name('library.show');

Route::get('/library/{id}/download', [LibraryTextController::class, 'download'])->name('library.download');
Route::get('/shared-dreams', [DreamController::class, 'sharedDreams'])->name('dreams.shared');


Route::post('/dreams/share', [DreamController::class, 'share'])->name('dreams.share'); // Share only
Route::middleware('auth')->group(function () {
    // ... your dream routes

    // 👇 MOVE these inside this group:
    Route::post('/dreams/{id}/like', [DreamController::class, 'like']);
    Route::post('/dreams/{dream}/comment', [DreamController::class, 'comment']);
    Route::get('/dreams/{id}/likes', [DreamController::class, 'getLikes']);
    Route::put('/comments/{id}', [\App\Http\Controllers\CommentController::class, 'update']);
    Route::delete('/comments/{id}', [\App\Http\Controllers\CommentController::class, 'destroy']);
});
