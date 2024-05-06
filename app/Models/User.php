<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserProfilePhotoTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'biography',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function profilePhotos(): HasMany
    {
        return $this->hasMany(UserProfilePhoto::class);
    }

    public function scopeIsUserProfile()
    {
        return $this->id === Auth::id();
    }

    public function currentProfilePhoto(): UserProfilePhoto|null
    {
        return $this->profilePhotos()->where('is_current', true)->where('type', UserProfilePhotoTypes::PROFILE_PHOTO->value)->first();
    }

    public function currentCoverPhoto(): UserProfilePhoto|null
    {
        return $this->profilePhotos()->where('is_current', true)->where('type', UserProfilePhotoTypes::COVER_PHOTO->value)->first();
    }

    protected function profilePhoto(): Attribute
    {
        // TODO: Replace this with a default profile photo
        $fallback = 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80';

        $path =  $this->currentProfilePhoto()
            ? 'storage/' . $this->currentProfilePhoto()->path
            : $fallback;

        return Attribute::make(
            get: static fn () => $path,
        );
    }

    protected function coverPhoto(): Attribute
    {
        // TODO: Replace this with a default profile photo
        $fallback = 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80';

        $path =  $this->currentCoverPhoto()
            ? 'storage/' . $this->currentCoverPhoto()->path
            : $fallback;

        return Attribute::make(
            get: static fn () => $path,
        );
    }
}
