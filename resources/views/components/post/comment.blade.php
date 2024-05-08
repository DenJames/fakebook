@props(['comment'])

<div class="flex flex-row gap-2 pt-1 pb-1" id="comment-{{ $comment->id }}">
    <div class="flex flex-row gap-2 w-[25px]">
        <img src="{{ asset($comment->user->profile_photo) }}" alt="profile picture" class="rounded-full w-[25px] h-[25px]">
    </div>
    <div class="w-[calc(100%-25px)]">
        <div>
            <div class="bg-gray-300 p-2 rounded-2xl flex flex-col max-w-fit">
                <span class="text-xs text-black font-bold">{{ $comment->user->name }}</span>
                <span class="text-md text-black">{{ $comment->content }}</span>
            </div>
        </div>
        <div>
            <div class="flex flex-row gap-3 text-xs pl-2">
                {{ formatDiffForHumans($comment->created_at) }}
                <span class="hover:underline like-comment {{ $comment->hasLiked() ? 'text-blue-500' : '' }}" data-comment-id="{{ $comment->id }}">Like comment</span>
                <span class="hover:underline replay-comment" data-post-id="{{ $comment->commentable->id }}" data-comment-id="{{ $comment->id }}">Replay</span>
                @if($comment->userIsAuthor())
                    <span class="hover:underline edit-comment" data-post-id="{{ $comment->commentable->id }}" data-comment-id="{{ $comment->id }}">Edit</span>
                    <span class="hover:underline delete-comment" data-post-id="{{ $comment->commentable->id }}" data-comment-id="{{ $comment->id }}">Delete</span>
                @endif
                @if($comment->likes->count())
                    <div class="flex flex-row gap-1">
                        {{ $comment->likes()->count() }} <span class="bg-blue-500 rounded-full p-0.5 text-white"><x-icons.like width="w-3" height="h-3"/></span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
