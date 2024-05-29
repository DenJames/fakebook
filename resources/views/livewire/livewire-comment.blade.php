<div class="flex flex-row gap-2 pt-1 pb-1 mt-2" id="comment-{{ $comment->id }}" x-data="{showCommentForm: false}">
    <div class="flex flex-row gap-2 w-[25px]">
        <img src="{{ asset($comment->user->profile_photo) }}" alt="profile picture"
             class="rounded-full w-[25px] h-[25px]">
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

                <button class="hover:underline like-comment {{ $comment->hasLiked() ? 'text-blue-500' : '' }}"
                        wire:click="likeComment">Like comment
                </button>
                @if ($this->comment->commentable->commentable_type !== 'App\Models\Comment')
                    <button class="hover:underline replay-comment" @click="showCommentForm = !showCommentForm">Reply
                    </button>
                @endif

                @if($comment->userIsAuthor())
{{--                    <button class="hover:underline edit-comment" data-post-id="{{ $comment->commentable->id }}"--}}
{{--                            data-comment-id="{{ $comment->id }}">Edit--}}
{{--                    </button>--}}
                    <button class="hover:underline delete-comment" wire:click="deleteComment"
                            wire:confirm="Are you sure that you want to delete this comment?">Delete
                    </button>
                @endif

                @if($comment->likes->count())
                    <div class="flex flex-row gap-1">
                        {{ $comment->likes()->count() }} <span class="bg-blue-500 rounded-full p-0.5 text-white"><x-icons.like
                                width="w-3" height="h-3"/></span>
                    </div>
                @endif
            </div>

            @if ($this->comment->commentable->commentable_type !== 'App\Models\Comment')
                <form wire:submit.prevent="replyComment" class="flex flex-row gap-2 items-center justify-center py-2"
                      x-show="showCommentForm" x-cloak>
                    <img src="{{ asset(Auth::user()->profile_photo) }}" alt="profile picture"
                         class="rounded-full w-[25px] h-[25px]">
                    <x-text-input type="text" placeholder="Write a comment..." id="content"
                                  class="w-full rounded-full post-comment-input" wire:model="content"
                                  wire:keydown.enter="replyComment" @keyup.enter="showCommentForm = !showCommentForm"/>
                    <button class="bg-blue-600 rounded-full py-2 px-4 text-white" @click="showCommentForm = !showCommentForm">Comment</button>
                </form>
            @endif

            @foreach($comments as $cmt)
                <livewire:livewire-comment :comment="$cmt" :key="$cmt->id"/>
            @endforeach
        </div>
    </div>
</div>

@script
<script>
    $wire.on('replyAdded', () => {
        document.getElementById('content').value = '';
    });
</script>
@endscript
