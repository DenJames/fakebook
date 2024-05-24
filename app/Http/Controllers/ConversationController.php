<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use App\Repositories\ConversationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function __construct(private readonly ConversationRepository $conversation)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch Conversations and Participants
        $conversations = Auth::user()?->conversations()->with('users')->latest('updated_at')->get();

        // Extract User IDs with Existing Conversations
        $existingConversationUserIds = $conversations->flatMap(function ($conversation) {
            return $conversation->users->where('id', '!=', Auth::id())->pluck('id');
        });

        $friendIds = Auth::user()?->friendships->where('accepted_at', '!=', null)->map(function ($friendship) {
            return $friendship->user_id === Auth::id() ? $friendship->friend_id : $friendship->user_id;
        });

        $users = User::whereNotIn('id', $existingConversationUserIds)->whereIn('id', $friendIds)->get();

        $conversations = $conversations->map(function ($conversation) {
            $conversation->participant = $conversation->users->where('id', '!=', Auth::id())->first();
            $conversation->latest_message = $conversation->messages->last()?->content ?? 'No messages yet';

            return $conversation;
        });

        return view('conversations.index', [
            'users' => $users,
            'conversations' => $conversations,
        ]);
    }

    public function show(Conversation $conversation)
    {
        // TODO: Refactor into policy?
        if (! $conversation->users->contains(Auth::id())) {
            abort(403);

        }

        return view('conversations.show', [
            'conversation' => $conversation,
            'messages' => $this->conversation->fetchMessages($conversation),
        ]);
    }

    public function start(Request $request, User $user)
    {
        if ($user->friendship(Auth::user()) === null) {
            abort(403);
        }

        $conversation = $request->user()->conversations()->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();

        if (! $conversation) {
            $conversation = Conversation::create();

            $conversation->users()->attach([
                $request->user()->id,
                $user->id,
            ], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $conversation->users()->updateExistingPivot($request->user()->id, [
            'is_visible' => true,
            'updated_at' => now(),
        ]);

        return redirect()->route('conversations.show', $conversation);
    }
}
