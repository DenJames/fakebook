<x-app-layout>
    <div class="grid grid-cols-1 lg:grid-cols-2 w-full gap-4">
        <div class="space-y-4">
            <div class="px-2">
                <h2 class="text-lg font-bold">Friend requests</h2>
            </div>

            <div class="max-h-[calc(100vh-200px)] overflow-y-auto px-2 space-y-3 py-1">
                @foreach($friendRequests as $friend)
                    <div>
                        <x-content.card content-classes="transition-all hover:scale-[101%] duration-300">
                            <div class="flex gap-x-2 items-center w-full justify-between">
                                <div class="flex gap-x-2 items-center">
                                    <a href="{{ route('profile.show', $friend->user) }}" class="transition-all duration-300 hover:scale-105">
                                       <div class="w-10 h-10">
                                            <img src="{{ asset($friend->user->profile_photo) }}"
                                                 alt="{{ $friend->user->name }}"
                                                 class="h-10 w-10 rounded-full">
                                       </div>
                                    </a>

                                    <div class="flex flex-col w-full">
                                        <a href="{{ route('profile.show', $friend->user) }}" class="font-bold truncate hover:underline">
                                            {{ $friend->user->name }}
                                        </a>
                                    </div>
                                </div>

                                <div class="text-black/70 flex gap-x-2 items-center h-full">
                                    <form action="{{ route('friends-request.accept', $friend->user) }}" method="POST" class="mt-1 transition-all hover:scale-105">
                                        @csrf

                                        <button type="submit" data-tooltip-target="add-friend">
                                            <x-icons.checkmark width="w-5" height="h-5" />
                                        </button>
                                    </form>

                                    <form action="{{ route('friends-request.destroy', $friend) }}" method="POST" class="mt-1 transition-all hover:scale-105">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" data-tooltip-target="add-friend">
                                            <x-icons.cross width="w-5" height="h-5" />
                                        </button>
                                    </form>

                                    <a href="{{ route('profile.show', $friend->user) }}" class="transition-all hover:scale-105">
                                        <x-icons.eye width="w-5" height="h-5"/>
                                    </a>
                                </div>
                            </div>
                        </x-content.card>
                    </div>
                @endforeach
            </div>
        </div>

        <hr class="block lg:hidden">

        <div class="space-y-4">
            <div class="px-2">
                <h2 class="text-lg font-bold">Friend list</h2>
            </div>

            <div class="max-h-[calc(100vh-200px)] overflow-y-auto px-2 space-y-3 py-1">
                @foreach($friends as $friend)
                    <div>
                        <x-content.card content-classes="transition-all hover:scale-[101%] duration-300">
                            <div class="flex items-center w-full">
                                <div class="flex gap-x-2 justify-between items-center w-full">
                                    <div class="flex gap-x-2 items-center">
                                        <a href="{{ route('profile.show', $friend->getProfile(Auth::user())) }}" class="hover:scale-105 transition-all duration-300">
                                            <div class="h-10 w-10">
                                                <img src="{{ asset($friend->getProfile(Auth::user())->profile_photo) }}"
                                                     alt="{{ $friend->getProfile(Auth::user())->name }}"
                                                     class="h-10 w-10 rounded-full">
                                            </div>
                                        </a>

                                        <a href="{{ route('profile.show', $friend->getProfile(Auth::user())) }}" class="font-bold truncate hover:underline">
                                            {{ $friend->getProfile(Auth::user())->name }}
                                        </a>
                                    </div>

                                    <div class="text-black/70 flex gap-x-2 items-center h-full">
                                        <a href="{{ route('conversations.start', $friend->getProfile(Auth::user())) }}" class="transition-all hover:scale-105">
                                            <x-icons.chat width="w-5" height="h-5"/>
                                        </a>

                                        <a href="{{ route('profile.show', $friend->getProfile(Auth::user())) }}" class="transition-all hover:scale-105">
                                            <x-icons.eye width="w-5" height="h-5"/>
                                        </a>

                                        <form action="{{ route('friends.destroy', $friend->getProfile(Auth::user())) }}" method="POST" class="mt-1 transition-all hover:scale-105">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button" data-tooltip-target="remove-friend" onclick="if(confirm('Are you sure you want to remove this user as your friend?')) this.form.submit()">
                                                <x-icons.trash width="w-5" height="h-5" />
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </x-content.card>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
