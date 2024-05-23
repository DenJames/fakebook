<?php

namespace App\Repositories\Post;

use App\Events\Post\PostCreateEvent;
use App\Events\Post\PostDeleteEvent;
use App\Events\Post\PostUpdateEvent;
use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Models\PostImage;
use App\Repositories\WordFilter\WordFilterRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

readonly class PostRepository
{
    public function __construct(private PostPictureRepository $postPictureUpload, private WordFilterRepository $wordFilterRepository)
    {
    }

    public function fetchPosts()
    {
        return Post::query()
            ->whereIn('user_id', array_merge(Auth::user()->friendsArray, [Auth::user()->id]))
            ->orderByDesc('id')
            ->get();
    }

    public function store(PostStoreRequest $request): JsonResponse
    {
        $content = $this->wordFilterRepository->replaceWordsInString($request->input('content'));
        if ($content instanceof RedirectResponse) {
            return response()->json(['error' => 'You used a banned word', 'type' => 'banned_word'], 400);
        }
        $post = $request->user()->posts()->create(array_merge($request->only(['visibility']), ['content' => $content]));
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

        $content = $this->wordFilterRepository->replaceWordsInString($request->input('content'));
        if ($content instanceof RedirectResponse) {
            return response()->json(['error' => 'You used a banned word', 'type' => 'banned_word'], 400);
        }

        $visibility = $post->visibility;

        $post->update(array_merge($request->only(['visibility']), ['edited_at' => now(), 'content' => $content]));

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
