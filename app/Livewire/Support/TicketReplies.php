<?php

namespace App\Livewire\Support;

use App\Models\Ticket as TicketModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketReplies extends Component
{
    public TicketModel $ticket;
    public string $content = '';

    public function getListeners()
    {
        return [
            'echo-private:ticket-replied-' . $this->ticket->user_id . ',TicketRepliedEvent' => '$refresh',
        ];
    }

    public function render()
    {
        return view('livewire.support.ticket-replies');
    }
}
