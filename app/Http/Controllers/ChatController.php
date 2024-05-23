<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __invoke()
    {
        $latestChat = Auth::user()?->conversations()->latest('updated_at')->first();

        if ($latestChat) {
            return to_route('conversations.show', $latestChat);
        }

        return to_route('conversations.index');
    }
}
