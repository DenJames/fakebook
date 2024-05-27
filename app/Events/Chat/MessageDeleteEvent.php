<?php

namespace App\Events\Chat;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;

class MessageDeleteEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public string $conversationId, public Message $message, public int $userId)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.'.$this->conversationId.'.'.$this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return "MessageDeleted";
    }

    public function broadcastWith(): array
    {
        $message = $this->message;
        if ($message->user_id === $this->userId) {
            $html = Blade::render('<x-chat.sender :message="$message" />', ['message' => $message]);
        } else {
            $html = Blade::render('<x-chat.receiver :message="$message" />', ['message' => $message]);
        }

        return [
            'message_id' => $message->id,
            'html' => $html,
        ];
    }
}
