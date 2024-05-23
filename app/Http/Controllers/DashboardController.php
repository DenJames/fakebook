<?php

namespace App\Http\Controllers;

use App\Repositories\Post\PostRepository;

class DashboardController extends Controller
{
    public function __construct(private readonly PostRepository $postRepository)
    {
    }

    public function __invoke()
    {
        return view('dashboard', [
            'posts' => $this->postRepository->fetchPosts(),
        ]);
    }
}
