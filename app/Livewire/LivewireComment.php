<?php

namespace App\Livewire;

use App\Events\CommentLiked;
use App\Events\CommentReply;
use App\Events\PostCommentDeletedEvent;
use App\Models\Comment;
use App\Models\Post as PostModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class LivewireComment extends Component
{
    public Comment $comment;
    public PostModel $post;
    public string $content = '';
    public Collection $comments;

    public function replyComment(): void
    {
        $this->validate([
            'content' => ['required', 'string'],
        ]);

        if ($this->comment->commentable->commentable_type === 'App\Models\Comment') {
            $this->comment = $this->comment->commentable;
        }

        $this->comment->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->content,
        ]);

        event(new CommentReply()); // Broadcast event
        $this->reset('content'); // resets the comment property
        $this->dispatch('replyAdded'); // Dispatch event to clear the comment input
    }

    public function likeComment(): void
    {
        if ($this->comment->hasLiked()) {
            $this->comment->likes()->where('user_id', Auth::id())->delete();
        } else {
            $this->comment->likes()->create([
                'user_id' => auth()->id(),
            ]);
        }

        CommentLiked::dispatch();
    }

    public function deleteComment(): void
    {
        $this->comment->userIsAuthor() ? $this->comment->delete() : abort(403);

        PostCommentDeletedEvent::dispatch();
    }

    #[On('echo:comment-reply,CommentReply')]
    public function loadComments(): void
    {
        $this->comments = $this->comment->comments()->latest()->limit(50)->get();
    }

    #[On('echo:comment-liked,CommentLiked')]
    public function refreshComment(): void
    {
        $this->comment = $this->comment->fresh();
    }

    public function mount(): void
    {
        $this->loadComments();
    }

    public function render()
    {
        return view('livewire.livewire-comment');
    }
}
