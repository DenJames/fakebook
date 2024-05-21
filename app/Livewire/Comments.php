<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Post;

class Comments extends Component
{
    public Post $post;
    public Collection $comments;

    public function getListeners()
    {
        return [
            "comAdded" => "mount",
            "echo:comment-posted,.CommentAdded" => "mount",
            "echo:post-comment-deleted,PostCommentDeletedEvent" => 'mount',
        ];
    }

    public function mount()
    {
        $this->comments =  $this->post->comments()->latest()->limit(2)->get();
    }

    public function render()
    {
        return view('livewire.comments');
    }
}
