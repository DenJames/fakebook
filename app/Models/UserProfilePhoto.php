<?php

namespace App\Models;

use App\Enums\UserProfilePhotoTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfilePhoto extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'type' => UserProfilePhotoTypes::class,
        'is_current' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
