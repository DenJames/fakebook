<div class="relative z-50 lg:hidden" role="dialog" aria-modal="true" x-cloak>
    <div class="fixed inset-0 bg-gray-900/80"
         x-show="isSidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-0 flex"
         x-show="isSidebarOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">
        <div class="relative mr-16 flex w-full max-w-xs flex-1">
            <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                <button type="button" class="-m-2.5 p-2.5" @click="isSidebarOpen = false">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex grow flex-col overflow-y-auto bg-blue-600 px-6 pb-4">
                <div class="flex h-16 shrink-0 items-center">
                    <a href="/" class="w-full">
                        <p class="text-4xl font-bold text-center w-full text-blue-100 border-b">
                            Fakebook
                        </p>
                    </a>
                </div>

                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-2">
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

                                    @if(Auth::user()->hasRole('admin'))
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
    </div>
</div>
