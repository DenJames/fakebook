@props(['contentClasses' => ''])

<div class="rounded-md bg-white shadow w-full relative flex items-center p-4 {{ $contentClasses }}">
    {{ $slot }}
</div>
