<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use App\Repositories\User\FriendshipRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends Controller
{
    public function __construct(private readonly FriendshipRepository $friendshipRepository)
    {
    }

    public function index()
    {
        return view('profile.friends', [
            'friendRequests' => Friendship::where('friend_id', Auth::id())->whereNull('accepted_at')->with('user')->paginate(10),
            'friends' => Friendship::where('user_id', Auth::id())->orWhere('friend_id', Auth::id())->whereNotNull('accepted_at')->paginate(10),
        ]);
    }

    public function destroy(User $user, Request $request)
    {
        return $this->friendshipRepository->remove($user, $request);
    }
}
