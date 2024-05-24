<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <link rel="manifest" href="manifest.json"/>
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
    @livewireStyles
</head>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<body class="h-full font-sans antialiased" x-data="{ isSidebarOpen: false }">
<div>
    <x-navigation.mobile-navigation/>

    <x-navigation.desktop-navigation/>

    <div
        class="sticky top-0 z-40 flex h-[53px] shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
        <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="isSidebarOpen = !isSidebarOpen">
            <span class="sr-only">Open sidebar</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
        </button>

        <!-- Separator -->
        <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

        <x-navigation.top-navigation/>
    </div>

    <main class="lg:pl-96 min-h-screen flex justify-center w-full p-4 lg:py-10 lg:px-32">
        {{ $slot }}
    </main>

    <x-pwa-banner/>
</div>

<x-success-message/>
<x-status-message/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.0.9/index.min.js"
        integrity="sha512-03Ucfdj4I8Afv+9P/c9zkF4sBBGlf68zzr/MV+ClrqVCBXWAsTEjIoGCMqxhUxv1DGivK7Bm1IQd8iC4v7X2bw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@stack('scripts')
@livewireScripts
</body>
</html>


