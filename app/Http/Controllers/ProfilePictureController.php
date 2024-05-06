<?php

namespace App\Http\Controllers;

use App\Http\Requests\BiographyFormRequest;
use App\Http\Requests\ProfilePictureFormRequest;
use App\Repositories\Profile\ProfileRepository;
use App\Repositories\User\ProfilePictureRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ProfilePictureController extends Controller
{
    public function __construct(private readonly ProfilePictureRepository $profilePictureRepository, private readonly ProfileRepository $profileRepository)
    {
    }

    public function store(ProfilePictureFormRequest $request): RedirectResponse|JsonResponse
    {
        return $this->profilePictureRepository->upload(Auth::user(), $request);
    }

    public function coverStore(ProfilePictureFormRequest $request): RedirectResponse|JsonResponse
    {
        return $this->profilePictureRepository->upload(Auth::user(), $request, 'cover-photos');
    }

    public function updateBiography(BiographyFormRequest $request): RedirectResponse
    {
        return $this->profileRepository->updateBiography($request->user(), $request);
    }
}
