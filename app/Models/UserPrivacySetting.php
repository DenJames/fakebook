<?php

namespace App\Models;

use App\Enums\ProfileVisibilityTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPrivacySetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'visibility_type' => ProfileVisibilityTypes::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
