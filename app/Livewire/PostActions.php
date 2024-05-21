<?php

namespace App\Livewire;

use App\Events\PostLikedEvent;
use App\Models\Post as PostModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostActions extends Component
{
    public PostModel $post;

    public function getListeners(): array
    {
        return [
            "echo:post-liked,PostLikedEvent" => 'refreshPost',
        ];
    }

    public function like()
    {
        if ($this->post->hasLiked()) {
            $this->post->likes()->where('user_id', Auth::id())->delete();
        } else {
            $this->post->likes()->create([
                'user_id' => Auth::id()
            ]);
        }

        PostLikedEvent::dispatch();
    }

    public function refreshPost(): void
    {
        $this->post = $this->post->fresh();
    }

    public function render()
    {
        return view('livewire.post-actions');
    }
}
