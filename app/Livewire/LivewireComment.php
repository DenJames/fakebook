<?php

namespace App\Livewire;

use App\Events\CommentLiked;
use App\Events\PostCommentDeletedEvent;
use App\Models\Comment;
use App\Models\Post as PostModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class LivewireComment extends Component
{
    public Comment $comment;
    public PostModel $post;

    public function likeComment(): void
    {
        if ($this->comment->hasLiked()) {
            $this->comment->likes()->where('user_id', Auth::id())->delete();
        } else {
            $this->comment->likes()->create([
                'user_id' => auth()->id()
            ]);
        }

        CommentLiked::dispatch();
    }

    public function deleteComment(): void
    {
        $this->comment->userIsAuthor() ? $this->comment->delete() : abort(403);

        PostCommentDeletedEvent::dispatch();
    }

    #[On('echo:comment-liked,CommentLiked')]
    public function refreshComment(): void
    {
        $this->comment = $this->comment->fresh();
    }

    public function render()
    {
        return view('livewire.livewire-comment');
    }
}
