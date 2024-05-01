<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('/chat', 'chat')->name('chat.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::group(['prefix' => 'conversations'], function () {
        Route::get('/', [ConversationController::class, 'index'])->name('conversations.index');
        Route::get('/start/{user}', [ConversationController::class, 'start'])->name('conversations.start');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
        Route::delete('/{conversation}', [ConversationController::class, 'destroy'])->name('conversations.destroy');
    });

    Route::group(['prefix' => 'messages'], function () {
        Route::post('/{conversation}', [MessageController::class, 'store'])->name('messages.store');
        Route::patch('/{message}', [MessageController::class, 'update'])->name('messages.update');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    });
});

require __DIR__.'/auth.php';
