<?php

namespace App\Repositories\Support;

use App\Http\Requests\TicketFormRequest;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketRepository
{
    public function fetchTickets(string $ticketStatus = 'open')
    {
        $tickets = Auth::user()?->tickets()->when($ticketStatus === 'open', function ($query) {
            return $query->whereNull('closed_at');
        }, function ($query) {
            return $query->whereNotNull('closed_at');
        })->latest('updated_at')->paginate(10);

        if (Auth::user()?->hasRole('admin')) {
            $tickets = Ticket::query()->when($ticketStatus === 'open', function ($query) {
                return $query->whereNull('closed_at');
            }, function ($query) {
                return $query->whereNotNull('closed_at');
            })->latest('updated_at')->paginate(10);
        }

        return $tickets;
    }

    public function show(Ticket $ticket)
    {
        if (! $ticket->isAuthor() && ! Auth::user()?->hasRole('admin')) {
            abort(403);
        }

        return $ticket;
    }

    public function store(TicketFormRequest $request)
    {
        return Auth::user()?->tickets()->create($request->validated());
    }

    public function destroy(Ticket $ticket)
    {
        if (! $ticket->isAuthor() && ! Auth::user()?->hasRole('admin')) {
            abort(403);
        }

        $ticket->delete();
    }
}
