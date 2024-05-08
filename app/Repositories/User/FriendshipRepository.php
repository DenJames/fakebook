<?php

namespace App\Repositories\User;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;

class FriendshipRepository
{
    public function add(User $user, Request $request): JsonResponse|RedirectResponse
    {
        // Prevent adding yourself as a friend
        if ($user->isUserProfile()) {
            $message = "You cannot add yourself.";

            return $request->wantsJson()
                ? response()->json(['error' => $message], 400)
                : redirect()->back()->withErrors($message);
        }

        Auth::user()?->friendships()->create([
            'friend_id' => $user->id,
        ]);

        $message = 'Your friend request has been sent!';
        return $request->wantsJson()
            ? response()->json(['success' => $message], 200)
            : redirect()->back()->with('success', $message);
    }

    public function remove(User $user, Request $request): JsonResponse|RedirectResponse
    {
        Auth::user()?->friendship($user)?->delete();

        $message = 'Friend has been removed!';
        return $request->wantsJson()
            ? response()->json(['success' => $message], 200)
            : redirect()->back()->with('success', $message);
    }

    public function removeRequest(Friendship $friendship, Request $request): JsonResponse|RedirectResponse
    {
        $friendship->delete();

        $message = 'Friend has been removed!';
        return $request->wantsJson()
            ? response()->json(['success' => $message], 200)
            : redirect()->back()->with('success', $message);
    }

    public function accept(User $user, Request $request)
    {

        $user->friendships()->where('friend_id', $request->user()->id)->update([
            'accepted_at' => now(),
        ]);

        $message = 'Friend request has been accepted!';
        return $request->wantsJson()
            ? response()->json(['success' => $message], 200)
            : redirect()->back()->with('success', $message);

    }

    public function friends(): JsonResponse
    {
        $friends1 = Auth::user()?->friendships->pluck('friend_id');
        $friends2 = Auth::user()?->friendships->pluck('user_id');

        $friends = User::whereIn('id', $friends1)->orWhereIn('id', $friends2)->pluck('id');
        $friends = $friends->filter(fn($id) => $id !== Auth::id());

        $friendIds = $friends->toArray();

        return response()->json(['friends' => $friends]);
    }
}
