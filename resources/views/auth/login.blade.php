<script>
    window.addEventListener('load', function() {
        Swal.fire({
            title: "Disclaimer",
            text: "Fakebook is a school project and is not affiliated with Facebook. It is solely intended for educational purposes.",
            icon: "warning"
        });
    });
</script>

<x-guest-layout>
    <div class="w-full flex justify-center">
        <div class="w-full lg:w-1/2">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')"/>

            <div class="w-full relative">
                @if(config('app.env') === 'local')
                    <div class="w-full flex justify-end">
                        <a href="{{ route('test-auth') }}">
                            <x-secondary-button class="text-white !bg-gray-900">
                                Test login
                            </x-secondary-button>
                        </a>
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')"/>
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                      :value="old('email')" required autofocus autocomplete="username"/>
                        <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')"/>

                        <div class="w-full">
                            <x-text-input id="password" class="block mt-1 w-full"
                                          type="password"
                                          name="password"
                                          required autocomplete="current-password"/>

                            <div class="flex lg:hidden justify-end mt-1">
                                <a href="{{ route('register') }}" class="text-xs text-gray-500 hover:text-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                    No account yet? <span class="underline">Register here!</span>
                                </a>
                            </div>
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                    </div>

                    <!-- Remember Me -->
                    {{--
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                   name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                    </div>
                    --}}


                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-blue-600 hover:text-blue-700 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all"
                               href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-form.primary-button class="ms-3">
                            {{ __('Log in') }}
                        </x-form.primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
