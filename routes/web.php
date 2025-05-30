<?php

use App\Http\Controllers\DrawingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('drawings.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DrawingController::class, 'dashboard'])->name('dashboard');

    Route::resource('drawings', DrawingController::class);

    Route::get('/drawings/{drawing}/reply', [ReplyController::class, 'create'])->name('replies.create');
    Route::post('/drawings/{drawing}/reply', [ReplyController::class, 'store'])->name('replies.store');
    Route::get('/replies/{reply}/edit', [ReplyController::class, 'edit'])->name('replies.edit');
    Route::put('/replies/{reply}', [ReplyController::class, 'update'])->name('replies.update');
    Route::delete('/replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
