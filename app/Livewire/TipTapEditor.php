<?php

namespace App\Livewire;

use App\Models\Post as PostModel;
use Livewire\Component;

class TipTapEditor extends Component
{
    public string $content = '';
    public string $editorId = 'timeline-textarea-status';
    public PostModel|null $post;

    public function render()
    {
        return view('livewire.tip-tap-editor');
    }
}
