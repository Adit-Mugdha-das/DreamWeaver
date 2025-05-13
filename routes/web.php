<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\DreamController;
use App\Http\Controllers\UserController;

/**
 * 🧼 Always force logout and redirect to login when visiting "/"
 */
Route::get('/', function (Request $request) {
    Auth::logout();                          // Force logout
    $request->session()->invalidate();       // Clear session
    $request->session()->regenerateToken();  // Regenerate CSRF token
    return redirect('/login');               // Go to login page
});

/**
 * 🔐 Login & Register Routes
 */
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.submit'); // ✅ Fixed route name

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
});
