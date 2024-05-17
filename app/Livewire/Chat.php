<?php

namespace App\Livewire;

use App\Events\Chat\MessageSendEvent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Repositories\ConversationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Livewire\Component;

class Chat extends Component
{
    public string $messageText = '';

    public Collection $messages;
    public Conversation $conversation;

    private User $user;
    private ConversationRepository $conversationRepository;

    public function getListeners(): array
    {
        return [
            "echo-private:conversation.{$this->conversation->id},.MessageCreated" => 'addMessage',
        ];
    }

    public function addMessage(array $payload)
    {
        // TODO: Refactor to avoid the extra DB query
        $message = Message::find($payload['message']['id']);
        $this->messages->push($message);
    }

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
        $this->messageText = '';
        $this->dispatch('message-sent');
    }

    public function mount(): void
    {
        $this->conversationRepository = app(ConversationRepository::class);
        $this->messages = $this->conversationRepository->fetchMessages($this->conversation);
    }

    public function boot()
    {
        $this->user = Auth::user();
    }

    public function render(): View
    {
        return view('livewire.chat', [
            'conversationId' => $this->conversation->id,
        ]);
    }
}
