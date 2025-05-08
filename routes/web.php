<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DreamController;


Route::get('/', function () {
    return redirect()->route('dreams.create');
});

Route::get('/dreams/create', [DreamController::class, 'create'])->name('dreams.create');
Route::post('/dreams', [DreamController::class, 'store'])->name('dreams.store');
Route::get('/dreams', [DreamController::class, 'index'])->name('dreams.index');
Route::post('/dreams/interpret', [DreamController::class, 'interpret']);