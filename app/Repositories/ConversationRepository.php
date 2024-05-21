<?php

namespace App\Repositories;

use App\Events\Chat\MessageReadEvent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ConversationRepository
{
    public function chunkMessages(Conversation $conversation, int $perPage = 8, string $sortBy = 'desc'): Collection|array
    {
        $conversation->messages()->whereNull('read_at')->whereNot('user_id', Auth::id())->each(function ($message) {
            $message->update(['read_at' => now()]);
            event(new MessageReadEvent($message->conversation->id, $message, $message->user_id));
        });

        return $conversation->messages()->withTrashed()->orderBy('created_at', $sortBy)->pluck('id')->chunk($perPage)->toArray();
    }

    public function fetchMessages(array $ids): Collection|array
    {
        return Message::withTrashed()->whereIn('id', $ids)->orderBy('created_at', 'desc')->get()->reverse();
    }
}
