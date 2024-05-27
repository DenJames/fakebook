<?php

namespace App\Http\Controllers;

use App\Events\Chat\MessageDeleteEvent;
use App\Events\Chat\MessageReadEvent;
use App\Events\Chat\MessageSendEvent;
use App\Events\Chat\MessageUpdateEvent;
use App\Http\Requests\MessageFormRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Repositories\MessageRepository;

class MessageController extends Controller
{
    public function __construct(private readonly MessageRepository $messageRepository)
    {
    }

    public function store(MessageFormRequest $request, Conversation $conversation)
    {
        $message = $this->messageRepository->storeMessage($conversation, $request);

        foreach ($conversation->users as $user) {
            event(new MessageSendEvent($conversation->id, $message, $user->id));
        }

        return response()->json($message);
    }

    public function update(Message $message, MessageFormRequest $request)
    {
        $this->messageRepository->updateMessage($message, $request);

        foreach ($message->conversation->users as $user) {
            event(new MessageUpdateEvent($message->conversation->id, $message, $user->id));
        }

        return response()->json($message);
    }

    public function destroy(Message $message)
    {
        $this->messageRepository->deleteMessage($message);

        foreach ($message->conversation->users as $user) {
            event(new MessageDeleteEvent($message->conversation->id, $message, $user->id));
        }

        return response()->noContent();
    }

    public function read(Message $message)
    {
        $this->messageRepository->markAsRead($message);

        foreach ($message->conversation->users as $user) {
            event(new MessageReadEvent($message->conversation->id, $message, $user->id));
        }

        return response()->json(['status' => 'success']);
    }
}
