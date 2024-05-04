<div class="max-w-xl text-sm text-gray-600">
    If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your
    recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has
    been compromised, you should also update your password.
</div>

<div class="mt-5 space-y-6">
    @foreach ($sessions as $session)
        <div class="flex items-center">
            <div>
                <x-icons.desktop width="w-8" height="h-8"/>
            </div>

            <div class="ml-3">
                <div class="text-sm text-gray-600">
                    {{ $session->user_agent }}
                </div>

                <div>
                    <div class="text-xs text-gray-500">
                        {{ $session->ip_address }},
                        @if ($session->id === session()->getId())
                            <span class="text-green-500 font-semibold">This device</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="flex items-center mt-5">
    <button type="submit"
            class="inline-flex items-center px-4 py-2 border border-black rounded font-semibold text-xs text-black uppercase tracking-widest hover:bg-highlightPurple active:bg-gray-900 focus:outline-none focus:border-highlightPurple focus:ring focus:ring-highlightPurple disabled:opacity-25 transition"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-logout-devices')"
    >
        Log Out Other Browser Sessions
    </button>

    <x-modal name="confirm-logout-devices" :show="$errors->logoutSessions->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.sessions.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Log Out Other Browser Sessions ') }}
            </h2>

            <p class="text-sm text-black/50">
                Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->logoutSessions->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Logout devices') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</div>
