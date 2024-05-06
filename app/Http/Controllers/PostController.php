<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Repositories\Post\PostPictureRepository;
use App\Repositories\Post\PostRepository;

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
}
