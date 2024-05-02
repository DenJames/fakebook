<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use App\Repositories\ConversationRepository;
use App\Services\MessageService;
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
    public function index(Request $request)
    {
        $conversations = $request->user()->conversations;
        dd($conversations);
        // return view('conversations.index', compact('conversations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        //
        dd($user);
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

        return view('chat', [
            'conversation' => $conversation,
            'messages' => $this->conversation->fetchMessages($conversation),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }

    /**
     * Start or check if a conversation exists with the given user.
     */
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
