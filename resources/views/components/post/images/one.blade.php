@props(['images'])

@foreach($images as $image)
    <div class="h-auto flex overflow-hidden justify-center items-center max-h-[380px]">
        <a data-fslightbox href="{{ asset($image->url) }}" class="w-full flex justify-center">
            <img src="{{ asset($image->url) }}" alt="{{ $image->name }}" class="post-image">
        </a>
    </div>
@endforeach
