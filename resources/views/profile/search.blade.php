<x-app-layout>
    <div class="flex flex-col gap-2 max-h-screen overflow-y-auto w-full lg:w-1/2">
        @foreach($users as $user)
            <x-content.card content-classes="border flex items-center gap-4 w-full">
                <img class="w-14 h-14 rounded-full" src="{{ asset($user->profile_photo) }}" alt="{{ $user->name }}">

                <a href="{{ route('profile.show', $user) }}" class="w-full">
                    {{ $user->name }}
                </a>


                <div class="flex justify-end w-full text-black/70">
                    <a href="{{ route('profile.show', $user) }}">
                        <x-icons.eye />
                    </a>
                </div>
            </x-content.card>
        @endforeach
    </div>

    <div class="w-full lg:w-1/2 mt-4">
        {{ $users->withQueryString()->links() }}
    </div>
</x-app-layout>
