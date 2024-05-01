<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{conversationId}.{userId}', function ($user, $conversationId, $userId) {
    return $user->conversations->contains('id', $conversationId) && $user->id == $userId;
});
