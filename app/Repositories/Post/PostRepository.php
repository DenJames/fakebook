<?php

namespace App\Repositories\Post;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

readonly class PostRepository
{

    public function __construct(private PostPictureRepository $postPictureUpload)
    {
    }

    public function store(PostStoreRequest $request): JsonResponse
    {
        $post = $request->user()->posts()->create($request->only(['content', 'visibility']));
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $this->postPictureUpload->upload($post, $image);
            }
        }

        return response()->json(['message' => 'Post created successfully'], 201);
    }
}
