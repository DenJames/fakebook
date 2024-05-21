<?php

namespace App\Livewire;

use App\Events\Post\CommentAdded;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostComment extends Component
{
    public Post $post;
    public string $comment = '';

    public function postComment()
    {
        $this->post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->comment
        ]);

        event(new CommentAdded()); // Broadcast event
        $this->reset('comment'); // resets the comment property
        $this->dispatch('commentAdded'); // Dispatch event to clear the comment input
    }

    public function render()
    {
        return view('livewire.post-comment', [
            'friendships' => Auth::user()->friendships
        ]);
    }
}
