<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'edited_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::observe(PostObserver::class);
    }

    public function scopeUserIsAuthor(): bool
    {
        return $this->user_id === Auth::id();
    }

    public function scopeAuthorisedToSee(): bool
    {
        return $this->visibility === 'public' || $this->user_id === Auth::id();
    }

    public function scopeHasLiked(): bool
    {
        return $this->likes->contains('user_id', Auth::id());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
