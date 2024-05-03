<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilePictureFormRequest;
use App\Repositories\User\ProfilePictureRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ProfilePictureController extends Controller
{
    public function __construct(private readonly ProfilePictureRepository $profilePictureRepository)
    {
    }

    public function store(ProfilePictureFormRequest $request): RedirectResponse
    {
        return $this->profilePictureRepository->uploadProfilePicture(Auth::user(), $request);
    }
}
