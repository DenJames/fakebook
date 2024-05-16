<?php

namespace App\Events\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;

class MessageSendEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Conversation $conversation, private readonly User $user) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.'.$this->conversation->id),
        ];
    }

    public function broadcastAs(): string
    {
        return "MessageCreated";
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->conversation->messages()->latest()->first(),
        ];
    }

//    public function broadcastWith(): array
//    {
//        $message = $this->message;
//        if ($message->user_id === $this->userId) {
//            $html = Blade::render('<x-chat.sender :message="$message" />', ['message' => $message]);
//        } else {
//            $html = Blade::render('<x-chat.receiver :message="$message" />', ['message' => $message]);
//        }
//
//        return [
//            'html' => $html,
//            'message_id' => $message->id,
//            'user_id' => $this->message->user_id,
//        ];
//    }
}
