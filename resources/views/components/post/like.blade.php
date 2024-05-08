@props(['post'])

<div class="flex flex-row gap-1">
    @if($post->likes()->count())
        <div class="bg-blue-500 text-white rounded-full p-1">
            <x-icons.like height="h-4" width="w-4"/>
        </div>
        <span class="like-count">{{ $post->likes()->count() }}</span>
    @endif
</div>
