<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// TODO: Important! Remove this before production
Route::get('/test-auth', function() {
    if (config('app.env') === 'production') {
        abort(404);
    }

    if (User::count() === 0) {
        User::factory()->create();
    }

    Auth::loginUsingId(User::first()->id);

    return redirect()->route('dashboard');

})->middleware('guest')->name('test-auth');


Route::middleware('auth')->group(function () {
    Route::get('/chat', function () {
        $latestChat = Auth::user()?->conversations()->latest('updated_at')->first();

        if ($latestChat) {
            return to_route('conversations.show', $latestChat);
        }

        return to_route('conversations.index');
})->name('chat.index');
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

require __DIR__ . '/auth.php';
