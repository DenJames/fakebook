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

class FriendRequestNotifications extends Component
{
    public DatabaseNotificationCollection $notifications;
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
        $this->echoChannel = "echo-private:friend-request-received-{$this->user->id},.FriendNotificationReceived";
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
        $this->notifications = $this->user->unreadNotifications->whereIn('type', $this->notificationTypes);
        $this->unreadNotificationsCount = $this->notifications->count();
    }

    public function render(): Application|Factory|View|FoundationApplication|IlluminateView
    {
        return view('livewire.friend-request-notifications');
    }
}
