<div>
    @foreach($comments as $comment)
        <livewire:livewire-comment :comment="$comment" :key="$comment->id"/>
    @endforeach

    {{-- Post new comment --}}
    <form wire:submit.prevent="postComment" class="flex flex-row gap-2 items-center justify-center py-2">
        <img src="{{ asset(Auth::user()->profile_photo) }}" alt="profile picture"
             class="rounded-full w-[25px] h-[25px]">

        <x-text-input type="text" placeholder="Write a comment..." id="comment-input"
                      class="w-full rounded-full post-comment-input" wire:model="content" wire:keydown.enter="postComment"/>

        <button class="bg-blue-600 rounded-full py-2 px-4 text-white">Comment</button>
    </form>
</div>

@script
<script>
    $wire.on('commentAdded', () => {
        document.getElementById('comment-input').value = '';
    });
</script>
@endscript
