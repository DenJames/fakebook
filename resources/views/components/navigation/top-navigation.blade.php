<div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
    <form class="relative flex flex-1 lg:pl-52" action="{{ route('profile.search') }}" method="GET">
        <label for="search-field" class="sr-only">Search</label>
        <svg class="pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400 text-black"
             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd"
                  d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                  clip-rule="evenodd"/>
        </svg>

        <input id="search-field"
               class="block h-full w-full border-0 py-0 pl-8 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm"
               placeholder="Search..." type="search" name="query">
    </form>

    @auth
        <div class="flex items-center gap-x-4 lg:gap-x-6" x-data="{notificationsOpen: false}">
            <livewire:user-notifications />

            <!-- Separator -->
            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>

            <!-- Profile dropdown -->

            <div class="relative">
                <x-dropdown align="left" content-classes="bg-white shadow">
                    <x-slot:trigger>
                        <button type="button"
                                class="-m-1.5 flex items-center p-1.5"
                                id="user-menu-button"
                                aria-expanded="false"
                                aria-haspopup="true"
                                @click="profileOpen = !profileOpen"
                        >
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full bg-gray-50"
                                 src="{{ asset(Auth::user()->profile_photo) }}"
                                 alt="">
                            <span class="hidden lg:flex lg:items-center">
                                <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">
                                    {{ Auth::user()->name }}
                                </span>
                                <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                     aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </button>
                    </x-slot:trigger>

                    <x-slot:content>
                        <a href="{{ route('profile.show', Auth::user()) }}"
                           class="block px-3 py-1 text-sm leading-6 text-gray-900" role="menuitem"
                           tabindex="-1" id="user-menu-item-0">Your profile</a>

                        <a href="{{ route('profile.edit') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900"
                           role="menuitem"
                           tabindex="-1" id="user-menu-item-0">Account settings</a>

                        <button class="block px-3 py-1 text-sm leading-6 text-gray-900"
                                @click.prevent="document.getElementById('logout-form').submit();" tabindex="-1"
                                id="user-menu-item-1">
                            {{ __('Logout') }}
                        </button>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </x-slot:content>
                </x-dropdown>
            </div>
        </div>
    @endauth
</div>
