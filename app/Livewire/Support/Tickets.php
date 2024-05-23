<?php

namespace App\Livewire\Support;

use App\Repositories\Support\TicketRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Tickets extends Component
{
    use WithPagination;

    public Collection  $tickets;
    public string $ticketStatus = 'open';

    public function changeStatus()
    {
        $this->tickets = app(TicketRepository::class)->fetchTickets($this->ticketStatus);
    }

    public function render()
    {
        return view('livewire.support.tickets');
    }
}
