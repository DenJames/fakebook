<?php

namespace App\Events\Post;

use App\Models\Post;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostDeleteEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Post $post, public bool $private = false)
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
        if ($this->private) {
            return [
                new PrivateChannel('me.'.$this->post->user_id.'.feeds'),
            ];
        }

        return [
            new PrivateChannel('friend.'.$this->post->user_id.'.feeds'),
        ];
    }

    public function broadcastAs(): string
    {
        return "PostDeleted";
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->post->id,
        ];
    }
}
