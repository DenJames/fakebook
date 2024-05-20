<?php

namespace App\Livewire;

use App\Models\Message;
use Livewire\Component;

class MessageSender extends Component
{
    public Message $message;

    public function destroy()
    {

        $this->message->delete();
    }

    public function render()
    {
        return view('livewire.message-sender');
    }
}
