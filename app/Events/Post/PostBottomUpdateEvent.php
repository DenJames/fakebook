<?php

namespace App\Events\Post;

use App\Models\Post;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostBottomUpdateEvent implements  ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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
        if ($this->private)
            return [
                new PrivateChannel('me.'.$this->post->user_id.'.feeds'),
            ];
        return [
            new PrivateChannel('friend.'.$this->post->user_id.'.feeds'),
        ];
    }

    public function broadcastAs(): string
    {
        return "PostBottomUpdated";
    }

    public function broadcastWith(): array
    {
        return [
            // 'html' => Blade::render('<x-post.post :post="$post"/>', ['post' => $this->post]),
            'url' => route('posts.show_bottom', $this->post),
            'id' => $this->post->id,
        ];
    }
}
