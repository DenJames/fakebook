<?php

namespace App\Livewire;

use App\Models\Post as ModelPost;
use Livewire\Component;

class Post extends Component
{
    public ModelPost $post;

    public function render()
    {
        return view('livewire.post');
    }
}
