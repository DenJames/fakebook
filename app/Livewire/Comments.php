<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use App\Models\Post;

class Comments extends Component
{
    public Post $post;
    public Collection $comments;

    public function getListeners()
    {
        return [
            "comAdded" => "loadComments",
            "echo:comment-posted,.CommentAdded" => "loadComments",
            "echo:post-comment-deleted,PostCommentDeletedEvent" => 'loadComments',
        ];
    }

    public function mount()
    {
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = $this->post->comments()->latest()->limit(2)->get();
    }

    public function render()
    {
        return view('livewire.comments');
    }
}
