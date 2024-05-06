<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileSearchFormRequest;
use App\Repositories\Profile\ProfileSearchRepository;

class ProfileSearchController extends Controller
{
    public function __construct(public readonly ProfileSearchRepository $profileSearchRepository)
    {
    }

    public function __invoke(ProfileSearchFormRequest $request)
    {
        $users = $this->profileSearchRepository->search($request->query('query'), true);

        return view('profile.search', [
            'users' => $users,
        ]);
    }
}
