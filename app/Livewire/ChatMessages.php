<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\Message;
use App\Repositories\ConversationRepository;
use Illuminate\Support\Collection;
use Livewire\Component;

class ChatMessages extends Component
{
    public array $ids = [];
    public Conversation $conversation;
    private ConversationRepository $conversationRepository;
    public Collection $messages;

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


    public function boot()
    {
         $this->conversationRepository = app(ConversationRepository::class);
         $this->messages = $this->conversationRepository->fetchMessages($this->ids);
    }

    public function render()
    {
        return view('livewire.chat-messages', [
            'messages' =>  $this->messages,
        ]);
    }
}
