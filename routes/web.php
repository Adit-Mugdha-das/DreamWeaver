<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DreamController;

// ✅ Modified to load a homepage view instead of redirecting
Route::get('/', function () {
    return view('welcome'); // You can change 'welcome' to 'home' if you rename the file
});

Route::get('/dreams/create', [DreamController::class, 'create'])->name('dreams.create');
Route::post('/dreams', [DreamController::class, 'store'])->name('dreams.store');
Route::get('/dreams', [DreamController::class, 'index'])->name('dreams.index');
Route::post('/dreams/interpret', [DreamController::class, 'interpret']);
