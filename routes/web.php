<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\DreamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\AvatarController;

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
 * 💤 Dream routes - Accessible only after login
 */
Route::middleware('auth')->group(function () {
    Route::get('/dreams/create', [DreamController::class, 'create'])->name('dreams.create');
    Route::post('/dreams', [DreamController::class, 'store'])->name('dreams.store');
    Route::get('/dreams', [DreamController::class, 'index'])->name('dreams.index');
    Route::post('/dreams/interpret', [DreamController::class, 'interpret']);
    Route::delete('/dreams/{dream}', [DreamController::class, 'destroy'])->name('dreams.destroy');

});

/**
 * ✉️ Forgot Password Flow using recovery Gmail
 */
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

// Send reset link based on recovery email
Route::post('/forgot-password', [UserController::class, 'sendResetLink'])->name('password.email');

// ✅ Reset Password Form (the one user opens via Gmail link)
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// ✅ Handle actual password reset submission (moved to controller)
Route::post('/reset-password', [UserController::class, 'resetPassword'])->middleware('guest')->name('password.update');

Route::get('/support', [SupportController::class, 'showNearbySupport'])->name('support');
Route::post('/get-nearby', [SupportController::class, 'getNearby'])->name('support.nearby');
Route::get('/dashboard', [DreamController::class, 'showDashboard'])->name('dashboard');
Route::get('/dreams/export/pdf', [DreamController::class, 'exportPdf'])->name('dreams.export.pdf');
Route::get('/dreams/{dream}/download', [DreamController::class, 'downloadSingle'])->name('dreams.download');
Route::get('/audio', function () {
    return view('dreams.audio');
})->name('dreams.audio');

Route::middleware('auth')->group(function () {
    Route::get('/avatar', [AvatarController::class, 'show'])->name('avatar.show');
    Route::post('/avatar/generate', [AvatarController::class, 'generate'])->name('avatar.generate');
});

Route::get('/dream-map', function () {
    $emotion = session('last_emotion') ?? 'neutral';
    $emotion = strtolower(session('last_emotion') ?? 'neutral');
    $unlocked = [
        'fear' => in_array($emotion, ['fear']),
        'joy' => in_array($emotion, ['joy']),
        'calm' => in_array($emotion, ['calm']),
    ];
    return view('dreams.dream_map', ['unlocked' => $unlocked]);
})->middleware('auth')->name('dream.map');

Route::get('/totems', function () {
    $tokens = Auth::user()->dream_tokens ?? [];
    return view('dreams.totems', compact('tokens'));
})->middleware('auth')->name('totems');

Route::get('/imagine', function () {
    return view('dreams.portal');
})->name('imagine.portal')->middleware('auth');
