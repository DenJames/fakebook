@props(['post'])

<div class="post-{{ $post->id }}-comment-section">
    @if($post->comments()->count())
        <div>
            @foreach($post->comments()->orderBy('id', 'desc')->limit(50)->get() as $comment)
                <x-post.comment :comment="$comment"/>
            @endforeach
        </div>
    @endif

    <livewire:post-comment :post="$post"/>
</div>
