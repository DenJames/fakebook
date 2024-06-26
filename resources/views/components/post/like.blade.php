@props(['post'])

<div class="flex flex-row items-center gap-1">
    @if($post->likes()->count())
        <div class="bg-blue-500 text-white rounded-full h-5 w-5 flex items-center justify-center p-1">
            <x-icons.like height="h-4" width="w-4"/>
        </div>
        <span class="like-count">{{ $post->likes()->count() }}</span>
    @endif
</div>
