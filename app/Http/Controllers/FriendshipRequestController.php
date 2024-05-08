<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use App\Repositories\User\FriendshipRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FriendshipRequestController extends Controller
{
    public function __construct(private readonly FriendshipRepository $friendshipRepository)
    {
    }

    public function store(User $user, Request $request): JsonResponse|RedirectResponse
    {
        return $this->friendshipRepository->add($user, $request);
    }

    public function destroy(Friendship $friendship, Request $request): JsonResponse|RedirectResponse
    {
        return $this->friendshipRepository->removeRequest($friendship, $request);
    }

    public function accept(User $user, Request $request)
    {
        return $this->friendshipRepository->accept($user, $request);
    }
}
