@props(['isSender' => true])

<div class="rounded {{ $isSender ? 'bg-blue-500 text-white' : 'bg-gray-300 text-black' }} w-1/2 h-24 relative flex items-center px-4">
    {{ $slot }}
</div>

