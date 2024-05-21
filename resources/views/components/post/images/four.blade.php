@props(['images'])

<div class="grid grid-cols-2 gap-0.5 w-full">
    @foreach($images as $image)
        <div class="h-auto overflow-hidden max-h-[380px]">
            <a data-fslightbox href="{{ asset($image->url) }}" class="w-full h-full">
                <div class="flex items-center justify-center h-full w-full">
                    <img src="{{ asset($image->url) }}" alt="{{ $image->name }}" class="post-image">
                </div>
            </a>
        </div>
    @endforeach
</div>
