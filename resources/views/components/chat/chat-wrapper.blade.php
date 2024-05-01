@props(['isSender' => true])

<div class="rounded {{ $isSender ? 'bg-blue-500 text-white' : 'bg-gray-300 text-black' }} w-full lg:w-1/2 min-h-16 relative flex items-center p-4 group">
    {{ $slot }}
</div>

