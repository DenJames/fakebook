<x-app-layout>
    <div class="flex flex-col gap-2 max-h-screen overflow-y-auto w-full lg:w-1/2">
        @foreach($users as $user)
            <x-content.card content-classes="border flex items-center gap-4 w-full">
                <img class="w-14 h-14 rounded-full" src="{{ asset($user->profile_photo) }}" alt="{{ $user->name }}">

                <a href="{{ route('profile.show', $user) }}" class="w-full">
                    {{ $user->name }}
                </a>


                <div class="flex gap-x-2 justify-end w-full text-black/70">
                    @if(is_null(Auth::user()->friendship($user)))
                        @if(Auth::user()->pendingFriendship($user))
                            <form class="revertFriendRequestForm"
                                  action="{{ route('friends-request.destroy', ['friendship' => Auth::user()->pendingFriendship($user)]) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        onclick="if(confirm('Are you sure you want to revert this friend request?')) this.form.submit();"
                                        data-tooltip-target="pending-friend-request">
                                    <x-icons.clock/>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('friends-request.store', $user) }}" method="POST">
                                @csrf

                                <button type="submit" data-tooltip-target="add-friend">
                                    <x-icons.user-plus/>
                                </button>
                            </form>
                        @endif
                    @else
                        @if(Auth::user()->pendingFriendship($user))
                            <form action="{{ route('friends-request.accept', $user) }}" method="POST">
                                @csrf

                                <button type="submit" data-tooltip-target="add-friend">
                                    <x-icons.checkmark/>
                                </button>
                            </form>

                            <form action="{{ route('friends-request.deny', $user) }}" method="POST">
                                @csrf

                                <button type="submit" data-tooltip-target="add-friend">
                                    <x-icons.cross/>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('friends.destroy', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="button" data-tooltip-target="add-friend"
                                        onclick="if(confirm('Are you sure you want to remove this user as your friend?')) this.form.submit()">
                                    <x-icons.trash/>
                                </button>
                            </form>
                        @endif
                    @endif

                    <a href="{{ route('profile.show', $user) }}">
                        <x-icons.eye/>
                    </a>
                </div>
            </x-content.card>
        @endforeach
    </div>

    <div class="w-full lg:w-1/2 mt-4">
        {{ $users->withQueryString()->links() }}
    </div>
</x-app-layout>
