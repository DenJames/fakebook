<x-app-layout>
    @push('title', $user->name . ' profile')

    <div class="bg-gray-100 rounded pb-4">
        <div class="w-full h-72 relative rounded rounded-b-none"
             style="background-image: url({{ asset($user->cover_photo) }}); background-size: cover; background-position: center">
            <div class="absolute right-2 bottom-2">
                <div class="flex gap-2">
                    @if($user->isUserProfile())
                        <x-secondary-button>
                            <label for="cover" class="flex gap-2 items-center hover:cursor-pointer">
                                <x-icons.camera/>
                                <span class="ml-2">Add cover photo</span>
                                <input id="cover" type="file" class="hidden" name="photo"
                                       onchange="uploadPhoto('cover')">
                            </label>
                        </x-secondary-button>
                    @else
                        @if(Auth::user()->friendship($user))
                            <form action="{{ route('friends.destroy', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <x-secondary-button type="submit">
                                    <x-icons.trash/>

                                    <span class="ml-2">
                                        Remove friend
                                    </span>
                                </x-secondary-button>
                            </form>

                            @if(Auth::user()->activeChat($user))
                                <a href="{{ route('conversations.show', Auth::user()->activeChat($user)) }}">
                                    <x-secondary-button>
                                        <x-icons.chat/>

                                        <span class="ml-2">
                                            Open chat
                                        </span>
                                    </x-secondary-button>
                                </a>
                            @else
                                <a href="{{ route('conversations.start', $user) }}">
                                    <x-secondary-button>
                                        <x-icons.chat/>

                                        <span class="ml-2">
                                            Message
                                        </span>
                                    </x-secondary-button>
                                </a>
                            @endif
                        @endif

                        @if(Auth::user()->pendingFriendship($user))
                            @if(Auth::user()->pendingFriendship($user)->user_id === Auth::id())
                                <form class="revertFriendRequestForm"
                                      action="{{ route('friends-request.destroy', ['friendship' => Auth::user()->pendingFriendship($user)]) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <x-secondary-button type="submit">
                                        <x-icons.clock/>

                                        <span class="ml-2">
                                            Revert friend request
                                        </span>
                                    </x-secondary-button>
                                </form>
                            @else
                                <form action="{{ route('friends-request.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" data-tooltip-target="add-friend">
                                        <x-icons.cross/>
                                    </button>
                                </form>
                            @endif
                        @endif

                        @if(!Auth::user()->friendship($user) && !Auth::user()->pendingFriendship($user))
                            <form action="{{ route('friends-request.store', $user) }}" method="POST">
                                @csrf

                                <x-secondary-button type="submit">
                                    <x-icons.user-plus/>

                                    <span class="ml-2">
                                        Add friend
                                    </span>
                                </x-secondary-button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="relative w-full flex gap-10">
            <div class="relative lg:-mt-14 flex items-center lg:items-start">
                <img class="w-32 h-32 lg:h-48 lg:w-48 rounded-full mx-2 lg:mx-8 z-0"
                     src="{{ asset($user->profile_photo) }}" alt="">

                @if($user->isUserProfile())
                    <div class="absolute right-12 bottom-0 z-10">
                        <button
                            class="rounded-full w-10 h-10 bg-gray-700 text-gray-100 flex items-center justify-center">
                            <label for="profile" class="flex gap-2 items-center hover:cursor-pointer">
                                <x-icons.camera/>
                                <input id="profile" type="file" class="hidden" name="photo"
                                       onchange="uploadPhoto('profile')">
                            </label>
                        </button>
                    </div>
                @endif
            </div>

            <div class="flex flex-col gap-y-2 mt-4">
                <h2 class="font-bold text-5xl">{{ $user->name }}</h2>

                <p class="text-black/70">{{ $user->activeFriendships->count() }} friends</p>
                <div class="flex">
                    @foreach($user->activeFriendships as $key => $friend)
                        <a class="h-10 w-10  -ml-3" href="{{ route('profile.show', $friend->getProfile($user)) }}"
                           style="z-index: {{ $user->friendships()->count() - $key }}">
                            <img class="w-full h-full rounded-full border-2 border-black/60"
                                 src=" {{ asset($friend->getProfile($user)->profile_photo) }}" alt="">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @if($user->profileVisible())
        <div class="mt-6 grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-4 space-y-6">
                <x-content.card content-classes="border">
                    <div class="w-full space-y-2">
                        <h2 class="text-2xl font-bold">Introduction</h2>

                        @if($user->privacySetting('show_biography'))
                            <p>
                                {{ $user->biography }}
                            </p>
                        @endif

                        @if($user->isUserProfile())
                            <x-secondary-button class="!bg-gray-200 w-full flex justify-center"
                                                x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'add-biography')">
                                Add biography
                            </x-secondary-button>
                        @endif

                        @if($user->privacySetting('show_join_date'))
                            <div class="flex gap-x-2">
                                <x-icons.clock/>

                                <span>Became a member: {{ $user->created_at->format('M Y') }}</span>
                            </div>
                        @endif
                    </div>
                </x-content.card>

                @if($user->privacySetting('show_photo_list'))
                    <x-content.card content-classes="border">
                        <div class="w-full space-y-2">
                            <h2 class="text-2xl font-bold">Pictures</h2>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 max-h-52 overflow-y-auto">
                                @foreach($user->profilePhotos()->where('type', \App\Enums\UserProfilePhotoTypes::PROFILE_PHOTO->value)->where('is_current', false)->get() as $photo)
                                    <a data-fslightbox href="{{ asset('storage/' . $photo->path) }}">
                                        <img class="w-full h-24 object-cover rounded"
                                             src="{{ asset('storage/' . $photo->path) }}"
                                             alt="">
                                    </a>
                                @endforeach

                                @foreach($user->posts as $post)
                                    @foreach($post->images as $photo)
                                        <a data-fslightbox href="{{ asset('storage/' . $photo->path) }}">
                                            <img class="w-full h-24 object-cover rounded"
                                                 src="{{ asset('storage/' . $photo->path) }}"
                                                 alt="">
                                        </a>
                                    @endforeach
                                @endforeach

                            </div>
                        </div>
                    </x-content.card>
                @endif

                @if($user->privacySetting('show_friend_list'))
                    <x-content.card content-classes="border">
                        <div class="w-full space-y-2">
                            <h2 class="text-2xl font-bold">Friends</h2>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 max-h-32 overflow-y-auto">
                                @foreach($user->activeFriendships as $friend)
                                    <div class="flex flex-col">
                                        <a href="{{ route('profile.show', $friend->getProfile($user)) }}">
                                            <img class="w-full h-24 object-cover rounded"
                                                 src="{{ asset($friend->getProfile($user)->profile_photo) }}"
                                                 alt="">

                                            <p class="text-sm truncate">
                                                {{ $friend->getProfile($user)->name }}
                                            </p>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </x-content.card>
                @endif
            </div>

            <div class="col-span-12 lg:col-span-8 space-y-6">
                @if($user->isUserProfile())
                    <x-timeline.status/>
                @endif

                @if($user->privacySetting('timeline_visible'))
                    <div class="lg:max-h-96 lg:max-h-[690px] lg:overflow-y-auto space-y-4 lg:pb-24">
                        @foreach($user->posts()->orderByDesc('id')->get() as $post)
                            <x-post.post :post="$post"/>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    <x-modal name="add-biography" focusable>
        <form method="post" action="{{ route('profile.biography.update') }}" class="p-6">
            @csrf
            @method('PUT')

            <h2 class="text-lg font-medium text-gray-900 ">
                {{ __('Update your profiles biography') }}
            </h2>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only"/>

                <textarea name="biography" class="w-full rounded min-32"></textarea>

                <x-input-error :messages="$errors->get('biography')" class="mt-2"/>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button type="submit" class="ms-3">
                    {{ __('Save') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
        <script>
            function uploadPhoto(photoType) {
                const fileInput = document.getElementById(photoType);
                if (fileInput.files.length > 0) {
                    const formData = new FormData();
                    formData.append('photo', fileInput.files[0]);

                    const url = photoType === 'cover' ? '/profile/upload-cover-photo' : '/profile/upload-profile-photo';
                    // Modify the URL to your endpoint
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            Toast.fire({
                                icon: 'success',
                                title: data.success
                            })

                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                }
            }
        </script>

    @endpush
</x-app-layout>
