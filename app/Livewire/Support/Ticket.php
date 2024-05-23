<?php

namespace App\Livewire\Support;

use App\Events\TicketRepliedEvent;
use App\Events\TicketStatusUpdatedEvent;
use App\Events\TicketUpdatedEvent;
use App\Models\Ticket as TicketModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Ticket extends Component
{
    public TicketModel $ticket;
    public string $reply = '';
    public string $content = '';

    public function getListeners()
    {
        return [
            'echo-private:ticket-status-updated-' . $this->ticket->user_id . ',TicketStatusUpdatedEvent' => '$refresh',
            'echo-private:ticket-updated-' . $this->ticket->user_id . ',TicketUpdatedEvent' => '$refresh',
        ];
    }

    public function toggleTicketStatus()
    {
        if (! Auth::user()?->isAdmin() && ! $this->ticket->isAuthor()) {
            abort(403);
        }

        $this->ticket->update([
            'closed_at' => $this->ticket->closed_at ? null : now(),
        ]);

        event(new TicketStatusUpdatedEvent($this->ticket));
    }

    public function updateTicket()
    {
        if (! Auth::user()?->isAdmin() && ! $this->ticket->isAuthor()) {
            abort(403);
        }

        $this->ticket->update([
            'content' => $this->content,
        ]);

        event(new TicketUpdatedEvent($this->ticket));
    }

    public function postReply()
    {
        if ((! $this->ticket->isAuthor() && ! Auth::user()?->isAdmin()) || $this->ticket->closed_at) {
            abort(403);
        }

        $this->ticket->replies()->create([
            'user_id' => auth()->id(),
            'content' => $this->reply,
        ]);


        event(new TicketRepliedEvent($this->ticket));
        $this->dispatch('replyPosted');
        $this->reset('reply');
    }

    public function mount()
    {
        $this->content = $this->ticket->content;
    }

    public function render()
    {
        return view('livewire.support.ticket');
    }
}
