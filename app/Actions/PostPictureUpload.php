<?php

namespace App\Actions;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostPictureUpload
{
    public function execute(Post $post, UploadedFile $file): bool|array
    {
        $fileHash = md5($file->getContent() . $post->id . Auth::id() . Str::random(6));

        $path = 'posts/' . $post->user->id . '/' . $post->id . '/' . $fileHash . '.' . $file->extension();

        if (! Storage::disk('public')->put($path, $file->getContent())) {
            Log::error('Failed to upload post photo for post: ' . $post->id);

            return false;
        }

        return [
            'image_id' => null,
            'file_path' => $path,
            'file_hash' => $fileHash,
        ];
    }
}
