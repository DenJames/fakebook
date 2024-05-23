<?php

namespace App\Repositories\Support;

use App\Http\Requests\TicketReplyFormRequest;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\Auth;

class TicketReplyRepository
{
    public function store(Ticket $ticket, TicketReplyFormRequest $request, )
    {
        if (! $ticket->isAuthor() && ! Auth::user()?->hasRole('admin')) {
            abort(403);
        }

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'content' => $request->get('content'),
        ]);
    }
}
