<?php

namespace App\Repositories\Post;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function destroy(Post $post): JsonResponse
    {
        if ($post->user_id !== Auth::user()->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
