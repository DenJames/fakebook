<x-app-layout>
    <div class="grid grid-cols-1 lg:grid-cols-2 w-full gap-4">
        <div class="space-y-4">
            <div class="px-2">
                <h2 class="text-lg font-bold">Active Chats</h2>
            </div>

            <div class="max-h-[calc(100vh-200px)] overflow-y-auto px-2 space-y-3 py-1">
                @foreach($conversations as $conversation)
                    <div>
                        <a href="{{ route('conversations.show', $conversation->id) }}">
                            <x-content.card content-classes="transition-all hover:scale-[101%] duration-300">
                                {{-- Unread message ping --}}
                                @if($conversation->hasUnreadMessages())
                                    <span class="absolute top-3 right-4 flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-600 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-600"></span>
                                </span>
                                @endif

                                <div class="flex flex-col w-full">
                                    <div class="flex gap-x-2 items-center">
                                        <img src="{{ asset($conversation->participant->profile_photo) }}"
                                             alt="{{ $conversation->participant->name }}"
                                             class="h-10 w-10 rounded-full">

                                        <div class="flex flex-col gap-2">
                                            <p class="font-bold truncate">
                                                {{ $conversation->participant->name }}
                                            </p>

                                            @if($conversation->messages->last())
                                                <small class="text-black/60">
                                                    {{ $conversation->messages->last()->content }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>

                                    @if($conversation->messages->last())
                                        <small class="text-end">
                                            {{ formatDiffForHumans($conversation->messages->last()->created_at) }}
                                        </small>
                                    @endif
                                </div>
                            </x-content.card>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <hr class="block lg:hidden">

        <div class="space-y-4">
            <div class="px-2">
                <h2 class="text-lg font-bold">Start a new chat</h2>
            </div>

            <div class="max-h-[calc(100vh-200px)] overflow-y-auto px-2 space-y-3 py-1">
                @foreach($users as $user)
                    <div>
                        <a href="{{ route('conversations.start', $user) }}">
                            <x-content.card content-classes="transition-all hover:scale-[101%] duration-300">
                                <div class="flex flex-col w-full">
                                    <div class="flex gap-x-2 items-center">
                                        <img src="{{ asset($user->profile_photo) }}"
                                             alt="{{ $user->name }}"
                                             class="h-10 w-10 rounded-full">

                                        <p class="font-bold truncate">
                                            {{ $user->name }}
                                        </p>
                                    </div>
                                </div>
                            </x-content.card>
                        </a>
                    </div>
                @endforeach
            </div>


        </div>
    </div>
</x-app-layout>
