<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketFormRequest;
use App\Models\Ticket;
use App\Repositories\Support\TicketCategoryRepository;
use App\Repositories\Support\TicketRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TicketsController extends Controller
{
    public function __construct(private readonly TicketRepository $ticketRepository, private readonly TicketCategoryRepository $ticketCategoryRepository)
    {
    }

    public function index()
    {
        return view('tickets.index', [
            'tickets' => $this->ticketRepository->fetchTickets(),
        ]);
    }

    public function show(Ticket $ticket)
    {
        return view('tickets.show', [
            'ticket' => $this->ticketRepository->show($ticket),
            'categories' => $this->ticketCategoryRepository->fetchAll(),
        ]);
    }

    public function create()
    {
        return view('tickets.create', [
            'categories' => $this->ticketCategoryRepository->fetchAll(),
        ]);
    }

    public function store(TicketFormRequest $request): RedirectResponse
    {
        $ticket = $this->ticketRepository->store($request);

        return dynamicResponse('support.tickets.show', $ticket, 'Ticket created successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        if (! $ticket->isAuthor() && ! Auth::user()?->isAdmin()) {
            abort(403);
        }

        $this->ticketRepository->destroy($ticket);

        return dynamicResponse('support.tickets.index', $ticket, 'The ticket has been deleted.');
    }
}
