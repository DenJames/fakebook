<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\FriendshipRequestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePictureController;
use App\Http\Controllers\ProfileSearchController;
use App\Http\Controllers\TicketReplyController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\UserSessionsController;
use App\Http\Middleware\EnsureUserIsNotBanned;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth', EnsureUserIsNotBanned::class])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Chat related routes
    Route::get('/chat', ChatController::class)->name('chat.index');

    Route::prefix('conversations')->name('conversations.')->group(function () {
        Route::get('/', [ConversationController::class, 'index'])->name('index');
        Route::get('/start/{user}', [ConversationController::class, 'start'])->name('start');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->name('show');
        Route::delete('/{conversation}', [ConversationController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('messages')->name('messages.')->group(function () {
        Route::post('/{conversation}', [MessageController::class, 'store'])->name('store');
        Route::patch('/{message}', [MessageController::class, 'update'])->name('update');
        Route::patch('/{message}/read', [MessageController::class, 'read'])->name('read');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy');
    });

    // Profile routes
    // TODO: Refactor into repositories
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::delete('/delete/sessions', UserSessionsController::class)->name('profile.sessions.destroy');

        Route::get('/{user}/show', [ProfileController::class, 'show'])->name('profile.show');

        Route::put('/update-biography', [ProfilePictureController::class, 'updateBiography'])->name('profile.biography.update');
        Route::put('/privacy-settings', [ProfileController::class, 'updatePrivacySettings'])->name('profile.private-settings.update');

        Route::post('/upload-profile-photo', [ProfilePictureController::class, 'store'])->name('profile-picture.store');
        Route::post('/upload-cover-photo', [ProfilePictureController::class, 'coverStore'])->name('cover-picture.store');
    });

    // Search
    Route::get('/search', ProfileSearchController::class)->name('profile.search');

    // Friends
    // TODO: Refactor into repositories
    Route::prefix('friends')->group(function () {
        Route::get('/', [FriendshipController::class, 'index'])->name('friends.index');
        Route::delete('/{user}', [FriendshipController::class, 'destroy'])->name('friends.destroy');


        Route::post('/{user}/request', [FriendshipRequestController::class, 'store'])->name('friends-request.store');
        Route::post('/{user}/accept', [FriendshipRequestController::class, 'accept'])->name('friends-request.accept');

        Route::delete('/{friendship}/request/delete', [FriendshipRequestController::class, 'destroy'])->name('friends-request.destroy');
    });

    // Feed/Posts
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
        Route::get('/{post}', [PostController::class, 'show'])->name('show');
        Route::get('/{post}/bottom', [PostController::class, 'show_bottom'])->name('show_bottom');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::patch('/{post}', [PostController::class, 'update'])->name('update');
        Route::post('/{post}/like', [PostController::class, 'like'])->name('like');
        Route::post('/{post}/comment', [PostController::class, 'comment'])->name('comment.store');
        Route::get('/{post}/images', [PostController::class, 'images'])->name('images');
        Route::post('/{post}/image', [PostController::class, 'image'])->name('image');
        Route::delete('/image/{postimage}', [PostController::class, 'delete_image'])->name('delete_image');
    });

    Route::prefix('comments')->name('comments.')->group(function () {
        Route::post('/{comment}/like', [CommentController::class, 'like'])->name('like');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
    });

    // Tickets
    Route::prefix('support')->as('support.')->withoutMiddleware(EnsureUserIsNotBanned::class)->group(function () {
        Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [TicketsController::class, 'create'])->name('tickets.create');
        Route::post('/tickets/create', [TicketsController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}/show', [TicketsController::class, 'show'])->name('tickets.show');
        Route::get('/tickets/{ticket}/delete', [TicketsController::class, 'destroy'])->name('tickets.destroy');

        // Replies
        Route::post('/tickets/{ticket}/reply', [TicketReplyController::class, 'store'])->name('tickets.replies.store');
    });


    // TODO: Refactor into repositories
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/friends', [FriendshipController::class, 'friends'])->name('friends');
    });

    Route::view('/banned', 'banned')->name('banned');
});

Route::get('/test-auth', function() {
    if (User::count() === 0) {
        User::factory()->create();
    }

    Auth::loginUsingId(User::first()->id);

    return redirect()->route('dashboard');

})->middleware('guest')->name('test-auth');

require __DIR__ . '/auth.php';
