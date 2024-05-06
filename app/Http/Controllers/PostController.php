<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Repositories\Post\PostPictureRepository;
use App\Repositories\Post\PostRepository;
use Illuminate\Http\Request;

@ini_set( 'upload_max_size' , '256M' );
@ini_set( 'post_max_size', '256M');
@ini_set( 'max_execution_time', '300' );

class PostController extends Controller
{

    public function __construct(private readonly PostPictureRepository $postPictureRepository, private readonly PostRepository $postRepository)
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
