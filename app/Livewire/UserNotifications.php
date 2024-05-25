<?php

namespace App\Livewire;

use App\Enums\FriendshipNotificationTypes;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View as IlluminateView;
use Livewire\Component;

class UserNotifications extends Component
{
    public DatabaseNotificationCollection $userNotifications;
    public int $unreadNotificationsCount = 0;
    private User $user;
    private array $notificationTypes;
    protected string $echoChannel;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->notificationTypes = array_map(static function ($case) {
            return $case->value;
        }, FriendshipNotificationTypes::cases());
        $this->echoChannel = "echo-private:friend-request-accepted-{$this->user->id},.FriendRequestAccepted";
    }

    public function getListeners(): array
    {
        return [
            $this->echoChannel => 'setNotifications',
        ];
    }

    public function markAsRead(string $notificationId): void
    {
        $this->user->unreadNotifications()->where('id', $notificationId)->update([
            'read_at' => now(),
        ]);

        $this->setNotifications();
    }

    public function markAllAsRead(): void
    {
        $this->user?->unreadNotifications()->update(['read_at' => now()]);

        $this->setNotifications();
    }

    public function mount(): void
    {
        $this->setNotifications();
    }

    public function setNotifications(): void
    {
        $this->userNotifications = $this->user->unreadNotifications->whereNotIn('type', $this->notificationTypes);
        $this->unreadNotificationsCount = $this->userNotifications->count();
    }

    public function render(): Application|Factory|View|FoundationApplication|IlluminateView
    {
        return view('livewire.user-notifications');
    }
}
