<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePictureController;
use App\Http\Controllers\UserSessionsController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Chat related routes
    Route::get('/chat', function () {
        $latestChat = Auth::user()?->conversations()->latest('updated_at')->first();

        if ($latestChat) {
            return to_route('conversations.show', $latestChat);
        }

        return to_route('conversations.index');
    })->name('chat.index');

    Route::group(['prefix' => 'conversations'], function () {
        Route::get('/', [ConversationController::class, 'index'])->name('conversations.index');
        Route::get('/start/{user}', [ConversationController::class, 'start'])->name('conversations.start');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
        Route::delete('/{conversation}', [ConversationController::class, 'destroy'])->name('conversations.destroy');
    });

    Route::group(['prefix' => 'messages'], function () {
        Route::post('/{conversation}', [MessageController::class, 'store'])->name('messages.store');
        Route::patch('/{message}', [MessageController::class, 'update'])->name('messages.update');
        Route::patch('/{message}/read', [MessageController::class, 'read'])->name('messages.read');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    });


    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::delete('/delete/sessions', UserSessionsController::class)->name('profile.sessions.destroy');

        Route::post('/profile-picture', [ProfilePictureController::class, 'store'])->name('profile-picture.store');
    });


});

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

require __DIR__ . '/auth.php';
