@props(['images'])

<div class="grid grid-cols-1">
    <div class="h-auto overflow-hidden max-h-[380px]">
        <a data-fslightbox href="{{ asset($images[0]->url) }}" class="w-full h-full">
            <div class="flex items-center justify-center h-full w-full">
                <img src="{{ asset($images[0]->url) }}" alt="{{ $images[0]->name }}" class="post-image">
            </div>
        </a>
    </div>
    <div class="grid grid-cols-2">
        <div class="h-auto overflow-hidden max-h-[380px]">
            <a data-fslightbox href="{{ asset($images[1]->url) }}" class="w-full h-full">
                <div class="flex items-center justify-center h-full w-full">
                    <img src="{{ asset($images[1]->url) }}" alt="{{ $images[1]->name }}" class="post-image">
                </div>
            </a>
        </div>
        <div class="h-auto overflow-hidden max-h-[380px]">
            <a data-fslightbox href="{{ asset($images[2]->url) }}" class="w-full h-full">
                <div class="flex items-center justify-center h-full w-full">
                    <img src="{{ asset($images[2]->url) }}" alt="{{ $images[2]->name }}" class="post-image">
                </div>
            </a>
        </div>
    </div>
</div>
