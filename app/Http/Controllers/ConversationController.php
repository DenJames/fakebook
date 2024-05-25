<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Repositories\ConversationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function __construct(private readonly ConversationRepository $conversation)
    {
    }

    public function index()
    {
        // Fetch Conversations and Participants
        $conversations = Auth::user()
            ?->conversations()
            ->with(['users', 'messages' => function ($query): void {
                $query->latest();
            }])
            ->addSelect(['latest_message' => Message::select('created_at')
                ->whereColumn('conversation_id', 'conversations.id')
                ->latest()
                ->take(1),
            ]) // Virtual column in order to sort chats by latest message
            ->orderByDesc('latest_message')
            ->get();

        // Extract User IDs with Existing Conversations
        $existingConversationUserIds = $conversations->flatMap(function ($conversation) {
            return $conversation->users->where('id', '!=', Auth::id())->pluck('id');
        });

        // Fetch User IDs of Friends
        $friendIds = Auth::user()?->friendships->where('accepted_at', '!=', null)->map(function ($friendship) {
            return $friendship->user_id === Auth::id() ? $friendship->friend_id : $friendship->user_id;
        });

        // Fetch Users with whom the user has no existing conversations
        $users = User::whereNotIn('id', $existingConversationUserIds)->whereIn('id', $friendIds)->get();

        // Map Conversations with Participants and Latest Message
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

        $conversation = $request->user()->conversations()->whereHas('users', function ($query) use ($user): void {
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
