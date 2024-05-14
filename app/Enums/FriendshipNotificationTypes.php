<?php

namespace App\Enums;

use App\Notifications\FriendRequestReceivedNotification;

enum FriendshipNotificationTypes: string
{
    case FRIEND_REQUEST_RECEIVED = FriendRequestReceivedNotification::class;
}
