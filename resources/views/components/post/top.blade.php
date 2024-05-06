@props(['post'])

<div class="flex flex-row gap-2">
    <img src="{{ asset($post->user->profile_photo) }}" alt="profile photo" class="w-[40px] h-[40px] rounded-full">
    <div class="flex justify-between w-full">
        <div>
            <p>{{ $post->user->name }}</p>
            <span class="text-xs flex flex-row">{{ formatDiffForHumans($post->created_at) }} · <x-post.visibility :visibility="$post->visibility" width="w-4" height="h-4"/></span>
        </div>
        <div>
            @if($post->userIsAuthor())
                <x-post.actions :post="$post"/>
            @endif
        </div>
    </div>
</div>
