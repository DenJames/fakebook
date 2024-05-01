<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
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
        //
        return view('chat', compact('conversation'));
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
