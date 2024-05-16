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
        $conversations = Auth::user()?->conversations()->latest('updated_at')->get()->map(function ($conversation) {
            $conversation->participant = $conversation->users()->where('user_id', '!=', Auth::id())->first();
            $conversation->latest_message = $conversation->messages->last()?->content ?? 'No messages yet';

            return $conversation;
        });

        $users = User::whereDoesntHave('conversations', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('conversations.index', [
            'users' => $users,
            'conversations' => $conversations,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        // TODO: Refactor into policy?
        if (! $conversation->users->contains(Auth::id())) {
            abort(403);
        }

        return view('conversations.show', [
            'conversation' => $conversation,
        ]);
    }

    public function start(Request $request, User $user)
    {
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
