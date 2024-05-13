<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class FriendRequestReceivedNotification extends Notification
{
    public function __construct(private readonly User $sender)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'sender' => $this->sender,
            'sent_from' => $this->sender->name,
            'sender_profile_photo' => $this->sender->profile_photo,
            'message' => 'You have received a friend request from:',
            'accept_url' => route('friends-request.accept', $this->sender->id),
            'deny_url' => route('friends-request.destroy', $this->sender->id),
        ];
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
