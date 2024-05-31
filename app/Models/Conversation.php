<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    use HasFactory;

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeParticipant()
    {
        return $this->users->where('id', '!=', Auth::id())->first();
    }

    public function hasUnreadMessages()
    {
        return $this->messages()->where('read_at', null)->where('user_id', '!=', Auth::user()->id)->exists();
    }
}
