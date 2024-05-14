<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class FriendRequestAcceptedNotification extends Notification
{
    public function __construct(private readonly User $user)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'user' => $this->user,
            'message' => $this->user->name . ' has accepted your friend request.',
        ];
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
