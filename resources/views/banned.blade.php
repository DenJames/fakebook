@vite('resources/css/app.css')
<body>
    <div class="flex h-full w-full items-center justify-center bg-gray-100 px-4 dark:bg-gray-900">
        <div class="max-w-md space-y-4 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-gray-50">Your account has been suspended</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">
                We're sorry, but your account has been suspended. You will regain access to the site on {{ \Illuminate\Support\Facades\Auth::user()->getBan()->formattedExpiresAt() }}.
            </p>
            <p class="text-lg text-gray-600 dark:text-gray-400">The reason is: {{ \Illuminate\Support\Facades\Auth::user()->getBan()->reason }}</p>
        </div>
    </div>
</body>
