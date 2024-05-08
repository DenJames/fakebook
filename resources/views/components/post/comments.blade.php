@props(['post'])

<div class="post-{{ $post->id }}-comment-section {{ $post->comments()->count() ? '' : 'hidden' }}">
    @if($post->comments()->count())
        <div>
            @foreach($post->comments()->orderBy('id', 'desc')->limit(2)->get() as $comment)
                <x-post.comment :comment="$comment"/>
            @endforeach
        </div>
    @endif
    <div class="flex flex-row gap-2 items-center justify-center py-2">
        <img src="{{ asset(Auth::user()->profile_photo) }}" alt="profile picture" class="rounded-full w-[25px] h-[25px]">
        <x-text-input id="comment_input_{{ $post->id }}" type="text" placeholder="Write a comment..." class="w-full rounded-full post-comment-input" data-post-id="{{ $post->id }}"/>
    </div>
</div>
