<?php

namespace App\Livewire;

use App\Events\Post\CommentAdded;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Comments extends Component
{
    public Post $post;
    public string $content = '';
    public Collection $comments;

    public function getListeners()
    {
        return [
            "echo:post-comment-deleted,PostCommentDeletedEvent" => 'loadComments',
            "echo:comment-posted,.CommentAdded" => 'loadComments',
        ];
    }

    public function postComment(): void
    {
        $this->validate([
            'content' => ['required', 'string'],
        ]);

        $this->post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->content,
        ]);

        $this->dispatch('commentAdded'); // Dispatch event to clear the comment input
        $this->reset('content'); // resets the comment property
        event(new CommentAdded()); // Broadcast event
    }

    public function loadComments(): void
    {
        $this->comments = $this->post->comments()->latest()->limit(50)->get();
    }

    public function mount(): void
    {
        $this->loadComments();
    }

    public function render()
    {
        return view('livewire.comments');
    }
}
