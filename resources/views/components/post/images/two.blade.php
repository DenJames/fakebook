@props(['images'])

<div class="flex flex-col gap-0.5 w-full">
    @foreach($images as $image)
        <div class="h-auto flex overflow-hidden justify-center items-center max-h-[380px]">
            <img src="{{ asset($image->url) }}" alt="{{ $image->name }}" class="post-image">
        </div>
    @endforeach
</div>