<?php

namespace App\Events\Post;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function broadcastOn(): Channel
    {
        return new Channel('comment-posted');
    }

    public function broadcastAs(): string
    {
        return 'CommentAdded';
    }

    public function broadcastWith(): array
    {
        return [
            'post' => 'some test',
        ];
    }
}
