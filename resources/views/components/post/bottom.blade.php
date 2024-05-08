@props(['post'])


<div class="divide-y divide-solid post-bottom-content">
    @if($post->likes()->count() || $post->comments()->count())
        <div class="grid grid-cols-2 gap-2 pb-1 pt-1">
            <x-post.like :post="$post"/>

            <div class="flex flex-row justify-end gap-1">
                <div class="flex flex-row gap-4">
                    @if($post->comments()->count())
                        <span>{{ $post->comments()->count() }} comment{{ $post->comments()->count() == 1 ? '' : 's' }}</span>
                    @endif
    {{--                <span>9 shars</span>--}}
                </div>
            </div>
        </div>
    @endif
    <div class="grid grid-cols-{{ $post->comments()->count() ? '2' : '3' }} pt-1">
        <div class="flex flex-row gap-2 items-center justify-center hover:bg-gray-300 py-1 rounded-md like-post {{ $post->hasLiked() ? 'text-blue-500' : '' }}" data-post-id="{{ $post->id }}">
            <x-icons.like/>
            <span>Like post</span>
        </div>
        <div class="flex flex-row gap-2 items-center justify-center hover:bg-gray-300 py-1 rounded-md comment-post {{ $post->comments()->count() ? 'hidden' : '' }}" data-post-id="{{ $post->id }}">
            <x-icons.chat/>
            <span>Comment</span>
        </div>
        <div class="flex flex-row gap-2 items-center justify-center hover:bg-gray-300 py-1 rounded-md share-post" data-post-id="{{ $post->id }}">
            <x-icons.share/>
            <span>Share</span>
        </div>
    </div>
    <x-post.comments :post="$post"/>
</div>


