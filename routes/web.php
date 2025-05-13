<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DreamController;
use App\Http\Controllers\UserController;

// ✅ Redirect to login if not authenticated, otherwise show welcome page
Route::get('/', function () {
    return Auth::check() ? view('welcome') : redirect('/login');
});

// ✅ Dream Routes (only for logged-in users)
Route::middleware('auth')->group(function () {
    Route::get('/dreams/create', [DreamController::class, 'create'])->name('dreams.create');
    Route::post('/dreams', [DreamController::class, 'store'])->name('dreams.store');
    Route::get('/dreams', [DreamController::class, 'index'])->name('dreams.index');
    Route::post('/dreams/interpret', [DreamController::class, 'interpret']);
});

// ✅ Login & Logout Routes
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
