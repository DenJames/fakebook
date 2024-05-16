<?php

namespace App\Http\Controllers;

use App\Events\Chat\MessageDeleteEvent;
use App\Events\Chat\MessageReadEvent;
use App\Events\Chat\MessageSendEvent;
use App\Events\Chat\MessageUpdateEvent;
use App\Http\Requests\MessageFormRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Livewire\Chat;

class MessageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Conversation $conversation, MessageFormRequest $request): void
    {
        $user = $request->user();
        $message = $conversation->messages()->create([
            'user_id' => $user->id,
            'content' => $request->get('message'),
        ]);

        event(new MessageSendEvent($conversation, $user));
        $this->emitTo(Chat::class, 'messageSent', $message->id); // Assuming your Livewire component is named Chat
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        $message->update([
            'content' => $request->get('message'),
            'edited_at' => now(),
        ]);

        foreach ($message->conversation->users as $user) {
            event(new MessageUpdateEvent($message->conversation->id, $message, $user->id));
        }

        return response()->json($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();

        foreach ($message->conversation->users as $user) {
            event(new MessageDeleteEvent($message->conversation->id, $message, $user->id));
        }

        return response()->noContent();
    }

    public function read(Message $message)
    {
        $message->update([
            'read_at' => now(),
        ]);

        foreach ($message->conversation->users as $user) {
            event(new MessageReadEvent($message->conversation->id, $message, $user->id));
        }

        return response()->json(['status' => 'success']);
    }
}
