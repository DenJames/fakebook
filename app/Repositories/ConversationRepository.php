<?php

namespace App\Repositories;

use App\Events\Chat\MessageReadEvent;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ConversationRepository
{
    public function fetchMessages(Conversation $conversation, int $limit = 0, string $sortBy = 'asc'): Collection|array
    {
        $query = $conversation->messages()->withTrashed();

        // Update messages read status
        $conversation->messages()->where('read_at', null)->where('user_id', '!=', auth()->id())->get()->each(function ($message): void {
            $message->update(['read_at' => now()]);
            event(new MessageReadEvent($message->conversation->id, $message, $message->user_id));
        });

        if ($limit > 0) {
            $query->limit($limit);
        }

        if ($sortBy === 'desc') {
            $query->orderByDesc('created_at');
        } else {
            $query->orderBy('created_at');
        }

        return $query->get();
    }

    public function fetchLatestMessage(User $user): Conversation|null
    {
        return $user->conversations()->latest('updated_at')->first();
    }
}
