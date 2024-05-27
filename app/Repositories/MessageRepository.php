<?php

namespace App\Repositories;

use App\Events\Chat\MessageReadEvent;
use App\Http\Requests\MessageFormRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class MessageRepository
{
    public function storeMessage(Conversation $conversation, MessageFormRequest $request): Message
    {
        return $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'content' => $request->get('message'),
        ]);
    }

    public function updateMessage(Message $message, MessageFormRequest $request): bool
    {
        return $message->update([
            'user_id' => $request->user()->id,
            'content' => $request->get('message'),
        ]);
    }

    public function deleteMessage(Message $message): bool
    {
        return $message->delete();
    }

    public function markAsRead(Message $message): bool
    {
        return $message->update([
            'read_at' => now(),
        ]);
    }
}
