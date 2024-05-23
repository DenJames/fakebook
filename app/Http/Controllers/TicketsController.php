<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketFormRequest;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TicketsController extends Controller
{
    public function index()
    {
        $tickets = Auth::user()?->tickets()->whereNull('closed_at')->latest('updated_at')->paginate(10);

        if (Auth::user()?->hasRole('admin')) {
            $tickets = Ticket::whereNull('closed_at')->latest('updated_at')->paginate(10);
        }

        return view('tickets.index', [
            'tickets' => $tickets,
            'solvedTickets' => Auth::user()?->tickets()->whereNotNull('closed_at')->latest('updated_at')->paginate(10),
        ]);
    }

    public function show(Ticket $ticket)
    {
        if (! $ticket->isAuthor() && ! Auth::user()?->hasRole('admin')) {
            abort(403);
        }

        return view('tickets.show', [
            'ticket' => $ticket,
            'categories' => TicketCategory::where('enabled', true)->get(),
        ]);
    }

    public function create()
    {
        return view('tickets.create', [
            'categories' => TicketCategory::where('enabled', true)->get(),
        ]);
    }

    public function store(TicketFormRequest $request): RedirectResponse
    {
        $ticket = Auth::user()?->tickets()->create($request->validated());

        return to_route('support.tickets.show', $ticket);
    }

    public function destroy(Ticket $ticket)
    {
        if (! $ticket->isAuthor() && ! Auth::user()?->hasRole('admin')) {
            abort(403);
        }

        $ticket->delete();

        return back()->with('success', 'Ticket deleted successfully.');
    }
}
