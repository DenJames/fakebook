<?php

namespace App\Repositories\Like;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Repositories\Post\PostPictureRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

readonly class LikeRepository
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

    public function edit(Post $post): string
    {
        return Blade::render('<x-timeline.edit :post="$post" modalId="post-edit-' . $post->id . '"/>', ['post' => $post]);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        if ($post->user_id !== Auth::user()->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $post->update(array_merge($request->only(['content', 'visibility']), ['edited_at' => now()]));

        return response()->json(['message' => 'Post updated successfully', 'id' => $post->id, 'view' => Blade::render('<x-post.post :post="$post"/>', ['post' => $post])]);
    }
}
