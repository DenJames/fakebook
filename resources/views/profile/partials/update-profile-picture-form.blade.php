<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile picture by uploading a new picture below.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile-picture.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf

        <img class="rounded-full w-32 h-32" src="{{ asset(Auth::user()->profile_photo) }}" alt="">

        <div>
            <x-input-label for="profile_picture" :value="__('Select a new profile picture')" />
            <x-text-input id="profile_picture" name="profile_picture" type="file" class="mt-1 block w-full" accept=".jpg,.jpeg,.gif,.png" required />
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
