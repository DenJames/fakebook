<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserSessionRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserSessionsController extends Controller
{
    public function __construct(public readonly UserSessionRepository $userSession)
    {
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $this->userSession->logoutOtherDevices($request);

        return redirect()->back()->with('success', 'Logged out of other browser sessions.');
    }
}
