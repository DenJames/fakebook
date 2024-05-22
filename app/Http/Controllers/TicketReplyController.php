<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketReplyFormRequest;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketReplyController extends Controller
{
    public function store(Ticket $ticket, TicketReplyFormRequest $request)
    {
        if (! $ticket->isAuthor() && ! Auth::user()?->hasRole('admin')) {
            abort(403);
        }

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'content' => $request->get('content'),
        ]);

        return back()->with('success', 'Reply posted successfully.');
    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
