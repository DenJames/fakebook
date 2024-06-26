<?php

namespace App\Repositories\Support;

use App\Http\Requests\TicketReplyFormRequest;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketReplyRepository
{
    public function store(Ticket $ticket, TicketReplyFormRequest $request): void
    {
        if (! $ticket->isAuthor() && ! Auth::user()?->isAdmin()) {
            abort(403);
        }

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'content' => $request->get('content'),
        ]);
    }
}
