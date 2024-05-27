<?php

namespace App\Http\Controllers;

use App\Repositories\ConversationRepository;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct(private readonly ConversationRepository $conversationRepository)
    {
    }

    public function __invoke()
    {
        $latestChat = $this->conversationRepository->fetchLatestMessage(Auth::user());

        if ($latestChat) {
            return to_route('conversations.show', $latestChat);
        }

        return to_route('conversations.index');
    }
}
