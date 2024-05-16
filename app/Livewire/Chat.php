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
    public Collection $messages;
    public Conversation $conversation;

    public string $messageText = '';

    private User $user;
    protected string $echoChannel;

    private ConversationRepository $conversationRepository;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->conversationRepository = app(ConversationRepository::class);
        $this->echoChannel = '';
    }

    public function getListeners(): array
    {
        return [
            "echo-private:conversation.{$this->conversation->id},.MessageCreated" => 'handleNewMessage',
        ];
    }

    public function addMessage(Message $message)
    {
        $this->messages->push($message);
    }

    public function handleNewMessage()
    {
        $this->messages = $this->conversationRepository->fetchMessages($this->conversation);
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

        $this->reset('messageText');
        $this->addMessage($message);
        event(new MessageSendEvent($this->conversation, $this->user));
    }

    public function mount(): void
    {
        $this->messages = $this->conversationRepository->fetchMessages($this->conversation);
        $this->echoChannel = "echo-private:conversation." . $this->conversation->id . "." . $this->user->id . ",.MessageCreated";
    }

    public function render(): View
    {
        return view('livewire.chat', [
            'conversationId' => $this->conversation->id,
        ]);
    }
}
