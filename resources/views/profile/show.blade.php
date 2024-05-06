<x-app-layout>
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
                        <x-secondary-button>
                            <x-icons.user-plus/>

                            <span class="ml-2">
                            Add friend
                        </span>
                        </x-secondary-button>
                    @endif
                </div>
            </div>
        </div>

        <div class="relative w-full flex gap-10">
            <div class="relative lg:-mt-14 flex items-center lg:items-start">
                <img class="w-32 h-32 lg:h-48 lg:w-48 rounded-full mx-2 lg:mx-8 z-0" src="{{ asset($user->profile_photo) }}" alt="">

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

                <p class="text-black/70">123 friends</p>
                <div class="flex">
                    @for($i = 0; $i < 5; $i++)
                        <img class="h-10 w-10 rounded-full -ml-3 border-2 border-black/60" style="z-index: {{ 5 - $i }}"
                             src=" {{ asset($user->profile_photo) }}" alt="">
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-4 space-y-6">
            <x-content.card content-classes="border">
                <div class="w-full space-y-2">
                    <h2 class="text-2xl font-bold">Introduction</h2>

                    <p>
                        {{ $user->biography }}
                    </p>

                    @if($user->isUserProfile())
                        <x-secondary-button class="!bg-gray-200 w-full flex justify-center"
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'add-biography')">
                            Add biography
                        </x-secondary-button>
                    @endif

                    <div class="flex gap-x-2">
                        <x-icons.clock/>

                        <span>Became a member: {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </x-content.card>

            <x-content.card content-classes="border">
                <div class="w-full space-y-2">
                    <h2 class="text-2xl font-bold">Pictures</h2>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 h-52 overflow-y-auto">
                        @foreach($user->profilePhotos()->where('type', \App\Enums\UserProfilePhotoTypes::PROFILE_PHOTO->value)->where('is_current', false)->get() as $photo)
                            <img class="w-full h-24 object-cover rounded" src="{{ asset('storage/' . $photo->path) }}"
                                 alt="">
                        @endforeach
                        @for($i = 0; $i < 18; $i++)

                        @endfor
                    </div>
                </div>
            </x-content.card>

            <x-content.card content-classes="border">
                <div class="w-full space-y-2">
                    <h2 class="text-2xl font-bold">Friends</h2>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 h-60 overflow-y-auto">
                        @for($i = 0; $i < 18; $i++)
                            <div class="flex flex-col">
                                <img class="w-full h-24 object-cover rounded" src="{{ asset($user->profile_photo) }}"
                                     alt="">

                                <p class="text-sm">
                                    {{ $user->name }}
                                </p>
                            </div>
                        @endfor
                    </div>
                </div>
            </x-content.card>
        </div>

        <div class="col-span-12 lg:col-span-8 space-y-6">
            <x-content.card content-classes="border">
                <div class="w-full space-y-2">
                    <h2 class="text-2xl font-bold">What's on your heart?</h2>

                    <x-text-input class="w-full" placeholder="Start writing something"/>
                </div>
            </x-content.card>

            <div class="max-h-96 lg:max-h-[calc(100vh-610px)] overflow-y-auto space-y-4">
                @for($i = 0; $i < 50; $i++)
                    <x-content.card content-classes="border">
                        <div class="w-full flex gap-4">
                            <img class="w-10 h-10 rounded-full" src="{{ asset($user->profile_photo) }}" alt="">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                            been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                            galley of type and scrambled it to make a type specimen book
                        </div>
                    </x-content.card>
                @endfor
            </div>
        </div>
    </div>

    <x-modal name="add-biography" :show="$errors->isNotEmpty()" focusable>
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
                            window.location.reload();
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                }
            }
        </script>

    @endpush
</x-app-layout>
