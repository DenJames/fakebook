<x-app-layout>
    <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col gap-2 max-h-screen overflow-y-auto col-span-2 lg:col-span-1">
            <h2 class="text-2xl">Friend requests</h2>

            @foreach($friendRequests as $friend)
                <x-content.card content-classes="border flex items-center gap-4 w-full">
                    <img class="w-14 h-14 rounded-full" src="{{ asset($friend->user->profile_photo) }}" alt="{{ $friend->name }}">

                    <a href="{{ route('profile.show', $friend) }}" class="w-full">
                        {{ $friend->user->name }}
                    </a>


                    <div class="flex gap-x-2 justify-end w-full text-black/70">
                        <form action="{{ route('friends-request.accept', $friend->user) }}" method="POST">
                            @csrf

                            <button type="submit" data-tooltip-target="add-friend">
                                <x-icons.checkmark />
                            </button>
                        </form>

                        <form action="{{ route('friends-request.destroy', $friend) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" data-tooltip-target="add-friend">
                                <x-icons.cross />
                            </button>
                        </form>

                        <a href="{{ route('profile.show', $friend->user) }}">
                            <x-icons.eye/>
                        </a>
                    </div>
                </x-content.card>
            @endforeach
        </div>

        <div class="flex flex-col gap-2 max-h-screen overflow-y-auto col-span-2 lg:col-span-1">
            <h2 class="text-2xl">Friend list</h2>

            @foreach($friends as $friend)
                <x-content.card content-classes="border flex items-center gap-4 w-full">
                    <img class="w-14 h-14 rounded-full" src="{{ asset($friend->getProfile(Auth::user())->profile_photo) }}" alt="{{ $friend->getProfile(Auth::user())->name }}">

                    <a href="{{ route('profile.show', $friend->getProfile(Auth::user())) }}" class="w-full">
                        {{ $friend->getProfile(Auth::user())->name }}
                    </a>

                    <div class="flex gap-x-2 justify-end w-full text-black/70">
                        <form action="{{ route('friends.destroy', $friend->getProfile(Auth::user())) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="button" data-tooltip-target="remove-friend" onclick="if(confirm('Are you sure you want to remove this user as your friend?')) this.form.submit()">
                                <x-icons.trash />
                            </button>
                        </form>

                        <a href="{{ route('profile.show', $friend->getProfile(Auth::user())) }}">
                            <x-icons.eye/>
                        </a>
                    </div>
                </x-content.card>
            @endforeach

        </div>
    </div>

    <div class="w-full lg:w-1/2 mt-4">
        {{ $friendRequests->withQueryString()->links() }}
    </div>
</x-app-layout>
