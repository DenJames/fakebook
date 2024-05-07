@props(['post'])

<div class="flex flex-row gap-2">
    <img src="{{ asset($post->user->profile_photo) }}" alt="profile photo" class="w-[40px] h-[40px] rounded-full">
    <div class="flex justify-between w-full">
        <div>
            <p>{{ $post->user->name }}</p>
            <span class="text-xs flex flex-row">{{ formatDiffForHumans($post->created_at) }} {!! $post->edited_at ? ' <span class="mx-1" data-tooltip-target="post-'.$post->id.'-edited">(edited)</span> ' : ' ' !!} ·  <x-post.visibility :visibility="$post->visibility" width="w-4" height="h-4"/></span>
            @if($post->edited_at)
                <div id="post-{{ $post->id }}-edited" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Edited: {{ $post->edited_at->diffForHumans() }}
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            @endif
        </div>
        <div>
            @if($post->userIsAuthor())
                <x-post.actions :post="$post"/>
            @endif
        </div>
    </div>
</div>
