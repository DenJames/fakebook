<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Repositories\Post\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function __construct(private readonly PostRepository $postRepository)
    {
    }

    public function store(PostStoreRequest $request): JsonResponse
    {
        return $this->postRepository->store($request);
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        return $this->postRepository->destroy($post);
    }

    public function edit(Post $post): string
    {
        return $this->postRepository->edit($post);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        return $this->postRepository->update($request, $post);
    }
}
