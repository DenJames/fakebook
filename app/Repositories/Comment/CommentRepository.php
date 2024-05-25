<?php

namespace App\Repositories\Comment;

use App\Events\Post\PostBottomUpdateEvent;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

readonly class CommentRepository
{
    public function like(Comment $comment): JsonResponse
    {
        $like = $comment->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            if ($comment->commentable instanceof Post) {
                event(new PostBottomUpdateEvent($comment->commentable));
                event(new PostBottomUpdateEvent($comment->commentable, true));
            }

            return response()->json(['message' => 'Like removed successfully']);
        }

        $comment->likes()->create(['user_id' => Auth::id()]);
        if ($comment->commentable instanceof Post) {
            event(new PostBottomUpdateEvent($comment->commentable));
            event(new PostBottomUpdateEvent($comment->commentable, true));
        }

        return response()->json(['message' => 'Comment liked successfully'], 201);
    }

    public function delete(Comment $comment): JsonResponse
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $comment->delete();

        if ($comment->commentable instanceof Post) {
            event(new PostBottomUpdateEvent($comment->commentable));
            event(new PostBottomUpdateEvent($comment->commentable, true));
        }

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
