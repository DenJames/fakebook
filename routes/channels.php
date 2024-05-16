<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    return $user->conversations->contains($conversationId);
});

Broadcast::channel('friend.{friend_id}.feeds', function (User $user, $friend_id) {
    $friends1 = $user?->friendships->pluck('friend_id');
    $friends2 = $user?->friendships->pluck('user_id');

    $friends = User::whereIn('id', $friends1)->orWhereIn('id', $friends2)->pluck('id');
    $friends = $friends->filter(fn($id) => $id !== Auth::id());

    $friendIds = $friends->toArray();

    return in_array($friend_id, $friendIds);
});

Broadcast::channel('me.{user_id}.feeds', function ($user, $user_id) {
    return $user->id == $user_id;
});

Broadcast::channel('friend-request-received-{receiverId}', function (User $user, $receiverId) {
    return $user->id == $receiverId;
});

Broadcast::channel('friend-request-accepted-{userId}', function (User $user, $userId) {
    return $user->id == $userId;
});
