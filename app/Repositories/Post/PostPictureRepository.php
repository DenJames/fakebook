<?php

namespace App\Repositories\Post;

use App\Actions\PostPictureUpload;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

readonly class PostPictureRepository
{

    public function __construct(private PostPictureUpload $postPictureUpload)
    {
    }

    public function upload(Post $post, UploadedFile $image): string
    {
        // If the profile picture upload fails, return an error message
        if (! $_image = $this->postPictureUpload->execute($post, $image)) {
            Log::debug('Failed to upload post picture');
            return 'Failed to upload post picture';
        }

        $post->images()->create([
            'name' => $image->getClientOriginalName(),
            'path' => $_image['file_path'],
            'file_hash' => $_image['file_hash'],
            'size' => $image->getSize(),
            'width' => getimagesize($image)[0],
            'height' => getimagesize($image)[1],
        ]);
        Log::debug('Post picture uploaded successfully');

        return 'Profile picture uploaded successfully';
    }
}
