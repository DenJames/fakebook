<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <link rel="manifest" href="manifest.json" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(!is_null(auth()->user()))
        <meta name="user-id" content="{{ auth()->user()->id }}">
    @endif

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="h-full font-sans antialiased">
<div class="flex h-full">
    <div class="hidden lg:block lg:w-1/2 sm:text-center h-full relative bg-blue-600 text-blue-100"
         style="background-image: url('https://i.imgur.com/ZiIXdW3.png'); background-size: cover; background-repeat: no-repeat">
        <div class="absolute w-full h-full bg-black/50 z-0"></div>

        <div class="p-6 z-10 relative w-full h-full ">
            <h1 class="text-5xl font-bold">
                <a href="/">
                    Fakebook
                </a>
            </h1>

            <!-- Todo: Missing images, they are not ready yet -->
            <div class="text-left flex flex-col h-full w-full justify-center gap-y-6 text-lg lg:-mt-10">
                <p>
                    Fakebook is a simplified social media platform designed to offer functionalities similar to
                    Facebook's basic features. It serves as a simple platform for social interaction and sharing
                    updates.
                </p>

                <p>
                    At Fakebook, we understand the importance of effortless interaction and seamless connectivity.
                    That's why we've crafted a platform that prioritizes user-friendliness and simplicity, ensuring that
                    anyone, from tech-savvy enthusiasts to casual users, can navigate and enjoy the social experience
                    with ease.
                </p>

                <p>
                    With Fakebook, simplicity meets sophistication, providing you with all the essential tools for
                    meaningful social interaction without the unnecessary clutter. Join us today and experience social
                    networking the way it was meant to be: simple, intuitive, and enjoyable.
                </p>

                @if(request()->is('login'))
                    <a href="{{ route('register') }}">
                        <button class="bg-green-600 text-green-100 rounded py-2 px-4 text-center hover:bg-green-500 transition-all w-full uppercase font-semibold">
                            Join us today!
                        </button>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full">
                        <button class="bg-green-600 text-green-100 rounded py-2 px-4 text-center hover:bg-green-500 transition-all w-full uppercase font-semibold">
                            Back to login
                        </button>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <main class="w-full md:w-1/0 sm:text-center bg-gray-50 h-12 h-full flex justify-center items-center relative px-4">
        {{ $slot }}
    </main>
</div>

@stack('scripts')
</body>
</html>


