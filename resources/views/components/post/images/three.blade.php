@props(['images'])

<div class="grid grid-cols-1">
    <div class="h-auto flex overflow-hidden justify-center items-center max-h-[380px]">
        <img src="{{ asset($images[0]->url) }}" alt="{{ $images[0]->name }}" class="post-image">
    </div>
    <div class="grid grid-cols-2">
        <div class="h-auto flex overflow-hidden justify-center items-center max-h-[380px]">
            <img src="{{ asset($images[1]->url) }}" alt="{{ $images[1]->name }}" class="post-image">
        </div>
        <div class="h-auto flex overflow-hidden justify-center items-center max-h-[380px]">
            <img src="{{ asset($images[2]->url) }}" alt="{{ $images[2]->name }}" class="post-image">
        </div>
    </div>
</div>
