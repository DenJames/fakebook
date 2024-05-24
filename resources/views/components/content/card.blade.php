@props(['contentClasses' => ''])

<div class="relative rounded bg-white shadow w-full relative flex items-center p-4 {{ $contentClasses }}">
    {{ $slot }}
</div>
