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

