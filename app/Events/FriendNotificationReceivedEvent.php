<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendNotificationReceivedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(private readonly User $sender, private readonly User $receiver)
    {
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('friend-request-received-' . $this->receiver->id);
    }

    public function broadcastAs(): string
    {
        return 'FriendNotificationReceived';
    }
}
