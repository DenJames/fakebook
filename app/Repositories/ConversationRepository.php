<?php

namespace App\Repositories;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Collection;

class ConversationRepository
{
    public function fetchMessages(Conversation $conversation, int $limit = 0, string $sortBy = 'asc'): Collection|array
    {
        $query = $conversation->messages();

        // Update messages read status
        $conversation->messages()->where('read_at', null)
            ->where('user_id', '!=' , auth()->id())
            ->update(['read_at' => now()]);

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

}
