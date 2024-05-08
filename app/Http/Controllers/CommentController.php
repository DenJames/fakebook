<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Repositories\Comment\CommentRepository;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function __construct(private readonly CommentRepository $commentRepository)
    {
    }

    public function like(Comment $comment): JsonResponse
    {
        return $this->commentRepository->like($comment);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        return $this->commentRepository->delete($comment);
    }
}
