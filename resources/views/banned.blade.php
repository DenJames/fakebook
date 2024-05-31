<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fakebook - You have been banned</title>

    @vite('resources/css/app.css')
</head>
<body class="h-screen">
<div class="flex h-full w-full items-center justify-center bg-gray-100 px-4">
    <div class="max-w-md space-y-4 text-center">
        <h1 class="text-4xl font-bold tracking-tight text-gray-900">Your account has been suspended</h1>
        <p class="text-lg text-gray-600">
            We're sorry, but your account has been suspended. You will regain access to the site
            on {{ \Illuminate\Support\Facades\Auth::user()->getBan()->formattedExpiresAt() }}.
        </p>
        <p class="text-lg text-gray-600">The reason
            is: {{ \Illuminate\Support\Facades\Auth::user()->getBan()->reason }}</p>
    </div>
</div>
</body>
</html>
