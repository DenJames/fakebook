<?php

namespace App\Repositories\Post;

use App\Http\Requests\PostStoreRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

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

    public function like(Post $post): JsonResponse
    {
        $like = $post->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            $html = Blade::render('<x-post.bottom :post="$post"/>', ['post' => $post]);
            return response()->json(['message' => 'Like removed successfully', 'html' => $html]);
        }

        $post->likes()->create(['user_id' => Auth::id()]);
        $html = Blade::render('<x-post.bottom :post="$post"/>', ['post' => $post]);
        return response()->json(['message' => 'Post liked successfully', 'html' => $html], 201);
    }

    public function comment(Request $request, Post $post): JsonResponse
    {
        $post->comments()->create(['user_id' => Auth::id(), 'content' => $request->comment]);

        return response()->json(['message' => 'Comment created successfully', 'html' => Blade::render('<x-post.bottom :post="$post"/>', ['post' => $post])], 201);
    }
}
