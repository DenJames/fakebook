<?php

namespace App\Livewire;

use App\Events\Chat\MessageSendEvent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Repositories\ConversationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Livewire\Component;

class Chat extends Component
{
    public string $messageText = '';
    public int $page = 1;

    public Conversation $conversation;
    public array $chunks = [];

    private User $user;

    public function sendMessage()
    {
        Validator::make(
            ['messageText' => $this->messageText],
            ['messageText' => ['required', 'max:255', 'string']]
        )->validate();

        $message = $this->conversation->messages()->create([
            'user_id' => $this->user->id,
            'content' => $this->messageText,
        ]);

        event(new MessageSendEvent($this->conversation, $this->user, $message));

        $this->dispatch('message-sent'); // Used in order to reset the input field.
    }

    public function hasMorePages(): bool
    {
        return $this->page < count($this->chunks);
    }

    public function incrementPage()
    {
        if (!$this->hasMorePages()) {
            return;
        }

        $this->dispatch('page-incremented');

        $this->page++;
    }

    public function boot()
    {
        $this->user = Auth::user();
        $conversationRepository = app(ConversationRepository::class);
        $this->chunks = $conversationRepository->chunkMessages($this->conversation);
    }

    public function render(): View
    {
        return view('livewire.chat', [
            'conversationId' => $this->conversation->id,
            'chunks' => $this->chunks,
        ]);
    }
}
