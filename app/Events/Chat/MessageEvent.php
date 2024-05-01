<?php

namespace App\Events\Chat;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;

class MessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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
        return 'MessageCreated';
    }

    public function broadcastWith(): array
    {
        if ($this->userId == $this->message->user_id) {
            $html = <<<'blade'
                        <div class="flex flex-col items-end gap-y-4 w-full">
                            <x-chat.sender>
                                <x-slot:username>
                                    {{ $username }}
                                </x-slot:username>
                                {{ $message }}
                            </x-chat.sender>
                        </div>
                    blade;
        } else {
            $html = <<<'blade'
                        <x-chat.receiver>
                            <x-slot:username>
                                {{ $username }}
                            </x-slot:username>
                            {{ $message }}
                        </x-chat.receiver>
                    blade;
        }

        return [
            'message' => $this->message->content,
            'sender' => $this->message->sender->name,
            'user_id' => $this->message->user_id,
            'html' => Blade::render($html, ['message' => $this->message->content, 'username' => $this->message->sender->name]),
        ];
    }
}
