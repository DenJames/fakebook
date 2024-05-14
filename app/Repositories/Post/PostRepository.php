<?php

namespace App\Repositories\Post;

use App\Events\Post\PostBottomUpdateEvent;
use App\Events\Post\PostCreateEvent;
use App\Events\Post\PostDeleteEvent;
use App\Events\Post\PostUpdateEvent;
use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Models\PostImage;
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

        if ($post->visibility === 'public' || $post->visibility === 'friends')
            event(new PostCreateEvent($post));

        event(new PostCreateEvent($post, true));


        return response()->json(['success' => 'Post created successfully'], 201);
    }

    public function destroy(Post $post): JsonResponse
    {
        if ($post->user_id !== Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($post->visibility === 'public' || $post->visibility === 'friends')
            event(new PostDeleteEvent($post));

        event(new PostDeleteEvent($post, true));
        $post->delete();

        return response()->json(['success' => 'Post deleted successfully'], 200);
    }

    public function edit(Post $post): string
    {
        return Blade::render('<x-timeline.edit :post="$post" modalId="post-edit-' . $post->id . '"/>', ['post' => $post]);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        if ($post->user_id !== Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $visibility = $post->visibility;

        $post->update(array_merge($request->only(['content', 'visibility']), ['edited_at' => now()]));

        //if it goes from private to public or friends send the create
        if ($visibility === 'private' && ($post->visibility === 'public' || $post->visibility === 'friends')) {
            event(new PostCreateEvent($post));
        } else if ($visibility !== 'private' && $post->visibility === 'private') {
            event(new PostDeleteEvent($post));
        } else if ($visibility !== 'private' && ($post->visibility === 'public' || $post->visibility === 'friends')) {
            event(new PostUpdateEvent($post));
        }

        event(new PostUpdateEvent($post, true));

        // return response()->json(['message' => 'Post updated successfully', 'id' => $post->id, 'view' => Blade::render('<x-post.post :post="$post"/>', ['post' => $post])]);
        return response()->json(['success' => 'Post updated successfully']);
    }

    public function like(Post $post): JsonResponse
    {
        $like = $post->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();

            event(new PostBottomUpdateEvent($post));
            event(new PostBottomUpdateEvent($post, true));
            return response()->json(['success' => 'Like removed successfully']);
        }

        $post->likes()->create(['user_id' => Auth::id()]);

        event(new PostBottomUpdateEvent($post));
        event(new PostBottomUpdateEvent($post, true));

        return response()->json(['success' => 'Post liked successfully'], 201);
    }

    public function comment(Request $request, Post $post): JsonResponse
    {
        $post->comments()->create(['user_id' => Auth::id(), 'content' => $request->comment]);

        event(new PostBottomUpdateEvent($post));
        event(new PostBottomUpdateEvent($post, true));

        return response()->json(['success' => 'Comment created successfully', 'html' => Blade::render('<x-post.bottom :post="$post"/>', ['post' => $post])], 201);
    }

    public function show(Post $post): string
    {
        return Blade::render('<x-post.post :post="$post"/>', ['post' => $post]);
    }

    public function show_bottom(Post $post): string
    {
        return Blade::render('<x-post.bottom :post="$post"/>', ['post' => $post]);
    }

    public function images(Post $post): JsonResponse
    {
        $response = [];
        foreach ($post->images as $image) {
            $response[] = [
                'url' => asset($image->url),
                'size' => $image->size,
                'name' => $image->name,
                'id' => $image->id
            ];
        }
        return response()->json($response);
    }

    public function image(Request $request, Post $post): JsonResponse
    {
        if ($request->hasFile('file')) {
            $id = $this->postPictureUpload->upload($post, $request->file('file'));

            return response()->json(['success' => 'Image uploaded successfully', 'id' => $id]);
        }

        return response()->json(['error' => 'No file uploaded']);
    }

    public function delete_image(PostImage $postImage): JsonResponse
    {
        $postImage->delete();

        return response()->json(['success' => 'Image deleted successfully']);
    }
}
