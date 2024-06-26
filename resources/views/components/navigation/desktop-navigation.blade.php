<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:block lg:w-64 lg:flex-col bg-blue-600">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 pb-4">
        <div class="flex h-16 shrink-0 items-center">
            <a href="/" class="w-full">
                <p class="text-4xl font-bold text-center w-full text-blue-100 border-b">
                    Fakebook
                </p>
            </a>
        </div>

        <nav class="flex flex-1 flex-col px-6">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        @guest
                            <x-navigation.side-nav-link :href="route('login')">
                                <x-slot:icon>
                                    <x-icons.signin />
                                </x-slot:icon>

                                Login
                            </x-navigation.side-nav-link>

                            <x-navigation.side-nav-link :href="route('register')">
                                <x-slot:icon>
                                    <x-icons.user-plus />
                                </x-slot:icon>

                                Register
                            </x-navigation.side-nav-link>
                        @else
                            <x-navigation.side-nav-link :href="route('dashboard')">
                                <x-slot:icon>
                                    <x-icons.home />
                                </x-slot:icon>

                                Dashboard
                            </x-navigation.side-nav-link>

                            <x-navigation.side-nav-link :href="route('conversations.index')">
                                <x-slot:icon>
                                    <x-icons.chat />
                                </x-slot:icon>

                                Chat
                            </x-navigation.side-nav-link>

                            <x-navigation.side-nav-link :href="route('friends.index')">
                                <x-slot:icon>
                                    <x-icons.user-plus />
                                </x-slot:icon>

                                Friendships
                            </x-navigation.side-nav-link>

                            <x-navigation.side-nav-link :href="route('support.tickets.index')">
                                <x-slot:icon>
                                    <x-icons.lifebuoy />
                                </x-slot:icon>

                                Support
                            </x-navigation.side-nav-link>

                            @if(Auth::user()->isAdmin())
                                <x-navigation.side-nav-link href="/admin" target="_blank">
                                    <x-slot:icon>
                                        <x-icons.user />
                                    </x-slot:icon>

                                    Admin panel
                                </x-navigation.side-nav-link>
                            @endif
                        @endguest
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
