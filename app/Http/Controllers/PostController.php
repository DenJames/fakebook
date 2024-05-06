<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Repositories\Post\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function __construct(private readonly PostRepository $postRepository)
    {
    }

    public function store(PostStoreRequest $request)
    {
        return $this->postRepository->store($request);
    }

    public function destroy(Request $request, Post $post)
    {
        return $this->postRepository->destroy($post);
    }
}
