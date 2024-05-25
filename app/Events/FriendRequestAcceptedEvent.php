<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestAcceptedEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(private readonly User $user)
    {
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('friend-request-accepted-' . $this->user->id);
    }

    public function broadcastAs(): string
    {
        return 'FriendRequestAccepted';
    }
}
