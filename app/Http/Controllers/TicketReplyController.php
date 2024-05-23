<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketReplyFormRequest;
use App\Models\Ticket;
use App\Repositories\Support\TicketReplyRepository;

class TicketReplyController extends Controller
{
    public function __construct(private readonly TicketReplyRepository $ticketReplyRepository)
    {
    }

    public function store(Ticket $ticket, TicketReplyFormRequest $request)
    {
        $this->ticketReplyRepository->store($ticket, $request);

        return back()->with('success', 'Reply posted successfully.');
    }
}
