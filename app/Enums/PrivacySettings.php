<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum PrivacySettings: string
{
    case ALLOW_FRIEND_REQUESTS = 'allow_friend_requests';
    case SHOW_BIOGRAPHY = 'show_biography';
    case SHOW_JOIN_DATE = 'show_join_date';
    case SHOW_FRIEND_LIST = 'show_friend_list';
    case SHOW_PHOTO_LIST = 'show_photo_list';
    case TIMELINE_VISIBLE = 'timeline_visible';

    public function toUpperSnakeCase(): string
    {
        return Str::title(Str::replace('_', ' ', $this->value));
    }
}
