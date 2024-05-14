<?php

namespace App\Observers;

use App\Models\PostImage;
use Illuminate\Support\Facades\Storage;

class PostImageObserver
{
    /**
     * Handle the PostImage "created" event.
     */
    public function created(PostImage $postImage): void
    {
        //
    }

    /**
     * Handle the PostImage "updated" event.
     */
    public function updated(PostImage $postImage): void
    {
        //
    }

    /**
     * Handle the PostImage "deleted" event.
     */
    public function deleted(PostImage $postImage): void
    {
        Storage::delete('public/'.$postImage->path);
    }

    /**
     * Handle the PostImage "restored" event.
     */
    public function restored(PostImage $postImage): void
    {
        //
    }

    /**
     * Handle the PostImage "force deleted" event.
     */
    public function forceDeleted(PostImage $postImage): void
    {
        //
    }
}
