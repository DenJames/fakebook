<?php

namespace App\Models;

use App\Observers\PostImageObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::observe(PostImageObserver::class);
    }

    protected function url(): Attribute
    {
        $path = 'storage/' . $this->path;

        return Attribute::make(
            get: static fn () => $path,
        );
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
