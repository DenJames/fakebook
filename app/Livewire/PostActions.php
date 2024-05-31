<?php

namespace App\Livewire;

use App\Events\PostLikedEvent;
use App\Models\Post as PostModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class PostActions extends Component
{
    public PostModel $post;

    public function getListeners()
    {
        return [
            'echo:post-liked,PostLikedEvent' => '$refresh',
            "echo:post-comment-deleted,PostCommentDeletedEvent" => '$refresh',
            "echo:comment-posted,.CommentAdded" => '$refresh',
        ];
    }

    public function like(): void
    {
        if ($this->post->hasLiked()) {
            $this->post->likes()->where('user_id', Auth::id())->delete();
        } else {
            $this->post->likes()->create([
                'user_id' => Auth::id(),
            ]);
        }

        PostLikedEvent::dispatch();
    }

    public function render()
    {
        return view('livewire.post-actions');
    }
}
