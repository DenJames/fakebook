<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserProfilePhotoTypes;
use App\Observers\UserObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

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

    public function privacySettings(): HasOne
    {
        return $this->hasOne(UserPrivacySetting::class);
    }

    public function friendships(): HasMany
    {
        return $this->hasMany(Friendship::class)->orWhere('friend_id', $this->id);
    }

    public function scopePendingFriendshipRequests(): HasMany
    {
        return $this->friendships()->whereNull('accepted_at');
    }

    public function scopeReceivedFriendshipRequests(): HasMany
    {
        return $this->friendships()->whereNull('accepted_at')->where('friend_id', Auth::id());
    }

    public function scopeIsUserProfile()
    {
        return $this->id === Auth::id();
    }

    public function pendingFriendship(self $user): Friendship|null
    {
        return Friendship::where(function ($query) use ($user) {
            $query->where('user_id', $this->id)
                ->where('friend_id', $user->id)
                ->whereNull('accepted_at');
        })
            ->orWhere(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('friend_id', $this->id)
                    ->whereNull('accepted_at');
            })
            ->first();
    }
    public function friendship(self $user): Friendship|null
    {
        return Friendship::whereNotNull('accepted_at')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $this->id)
                    ->where('friend_id', $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('friend_id', $this->id);
            })
            ->first();
    }

    public function IsFriendWith(User $user): bool
    {
        return in_array($user->id, $this->friendsArray);
    }

    public function getFriendsArrayAttribute()
    {
        $friendships = $this->friendships()->where('accepted_at', '!=', null)->get();

        $friendshipData = $friendships->map(function ($friendship) {
            return $friendship->user_id === $this->id ? $friendship->friend_id : $friendship->user_id;
        });

        // Remove duplicates
        $uniqueFriendshipData = $friendshipData->unique();

        return $uniqueFriendshipData->values()->toArray();
    }

    public function activeChat(self $user): Conversation|null
    {
        return $this->conversations()->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();
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

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin') || $this->hasPermissionTo('access admin panel');
    }
}
