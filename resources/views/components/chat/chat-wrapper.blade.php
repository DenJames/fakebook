@props(['isSender' => true, 'isTrashed' => false])

<div class="rounded {{ $isTrashed ? 'bg-transparent italic border-2' : ($isSender ? 'bg-blue-600 text-white' : 'bg-gray-200 text-black') }} w-full lg:w-1/2 min-h-16 relative flex items-center p-4 group">
    {{ $slot }}
</div>

