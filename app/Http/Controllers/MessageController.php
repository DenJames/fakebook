<?php

namespace App\Http\Controllers;

use App\Events\Chat\MessageEvent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Conversation $conversation)
    {
        $message = $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'content' => $request->get('message'),
        ]);

        foreach ($conversation->users as $user) {
            event(new MessageEvent($conversation->id, $message, $user->id));
        }


        return response()->json($message);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        $message->update($request->only('message'));

        return response()->json($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return response()->noContent();
    }
}
