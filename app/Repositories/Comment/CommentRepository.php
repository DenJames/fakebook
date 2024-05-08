<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

readonly class CommentRepository
{
    public function like(Comment $comment): JsonResponse
    {
        $like = $comment->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            $html = Blade::render('<x-post.comment :comment="$comment"/>', ['comment' => $comment]);
            return response()->json(['message' => 'Like removed successfully', 'html' => $html]);
        }

        $comment->likes()->create(['user_id' => Auth::id()]);
        $html = Blade::render('<x-post.comment :comment="$comment"/>', ['comment' => $comment]);
        return response()->json(['message' => 'Comment liked successfully', 'html' => $html], 201);
    }

    public function delete(Comment $comment): JsonResponse
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully', 'html' => Blade::render('<x-post.bottom :post="$post"/>', ['post' => $comment->commentable])], 200);
    }
}
