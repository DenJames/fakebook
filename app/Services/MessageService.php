<?php

namespace App\Services;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Collection;

class MessageService
{
    public function fetchMessages(Conversation $conversation, int $limit = 0, string $sortBy = 'asc'): Collection|array
    {
        $query = $conversation->messages();

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
