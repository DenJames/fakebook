<x-dropdown align="left" content-classes="bg-white shadow max-h-64 overflow-y-auto w-40 lg:w-72 -ml-6 lg:-ml-28">
    <x-slot:trigger>
        <button type="button"
                class="-mr-3 p-2.5 text-gray-400 hover:text-gray-500"
                id="user-menu-button"
                aria-expanded="false"
                aria-haspopup="true"
                @click="notificationsOpen = !notificationsOpen"
        >
            <span class="sr-only">View notifications</span>
            <div class="relative">
                <x-icons.bell />

                @if($unreadNotificationsCount)
                    <div
                        class="absolute -top-1 -right-1 min-h-4 min-w-4 px-1 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs">
                        {{ $unreadNotificationsCount }}
                    </div>
                @endif
            </div>
        </button>
    </x-slot:trigger>

    <x-slot:content>
        <div class="p-2 relative" @click.stop>
            @forelse($userNotifications as $notification)
                <div class="relative p-2" wire:key="notification-{{ $notification->id }}">
                    <button class="hover:scale-105 transition-all absolute top-3 right-1"
                            wire:click="markAsRead('{{ $notification->id }}')">
                        <x-icons.cross height="h-4" width="w-4"/>
                    </button>

                    <div class="flex gap-2">
                        <p class="text-sm text-black/60 max-w-[95%]">
                            {{ $notification->data['message'] }}

                            @if(!empty($notification->data['sender']))
                                <a href="{{ route('profile.show', $notification->data['sender']['id']) }}" class="font-bold hover:underline">
                                    {{ $notification->data['sender']['name'] ?? '' }}
                                </a>
                            @endif
                        </p>
                    </div>
                </div>
                <hr>
            @empty
                <p class="text-sm w-full text-center">You currently have no notifications</p>
            @endforelse

            @if($unreadNotificationsCount)
                <button class="text-sm text-gray-600 text-center w-full hover:underline mt-2" wire:click="markAllAsRead">
                    Mark all as read
                </button>
            @endif
        </div>
    </x-slot:content>
</x-dropdown>
