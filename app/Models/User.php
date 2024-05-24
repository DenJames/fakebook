<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\ProfileVisibilityTypes;
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

    public function friendships()
    {
        return $this->hasMany(Friendship::class, 'user_id')
            ->orWhere(function ($query) {
                $query->where('friend_id', $this->id);
            });
    }

    public function activeFriendships(): HasMany
    {
        return $this->hasMany(Friendship::class, 'user_id')
            ->whereNotNull('accepted_at')
            ->orWhere(function ($query) {
                $query->where('friend_id', $this->id)->whereNotNull('accepted_at');
            });
    }


    public function bans(): HasMany
    {
        return $this->hasMany(Ban::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketReplies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function scopePendingFriendshipRequests(): HasMany
    {
        return $this->friendships()->whereNull('accepted_at');
    }

    public function scopeReceivedFriendshipRequests(): HasMany
    {
        return $this->friendships()->whereNull('accepted_at')->where('friend_id', Auth::id());
    }

    public function scopeIsUserProfile(): bool
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
        return Friendship::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $this->id)
                    ->where('friend_id', $user->id)
                    ->whereNotNull('accepted_at');
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('friend_id', $this->id)
                    ->whereNotNull('accepted_at');
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

    public function profileVisible(): bool
    {
        $visibleToFriendsOnly = $this->privacySettings->visibility_type === ProfileVisibilityTypes::ONLY_FRIENDS;
        if ($visibleToFriendsOnly) {
            return $this->friendship(Auth::user()) !== null || $this->isUserProfile();
        }

        return $this->privacySettings->visibility_type === ProfileVisibilityTypes::PUBLIC || $this->isUserProfile();
    }

    public function widgetIsVisible(string $setting): bool
    {
        if (!isset($this->privacySettings->{$setting})) {
            return false;
        }

        return $this->privacySettings->{$setting} || $this->isUserProfile();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() || $this->hasPermissionTo('access admin panel');
    }

    public function isBanned(): bool
    {
        return $this->bans()->where('expires_at', '>', now())->exists();
    }

    public function isNotBanned(): bool
    {
        return !$this->isBanned();
    }

    public function getBan()
    {
        return $this->bans()->where('expires_at', '>', now())->first();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
