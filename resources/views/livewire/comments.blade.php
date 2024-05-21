<div>
    @if($post->comments()->count())
        <div>
            @foreach($comments as $comment)
                <livewire:livewire-comment :comment="$comment" :key="$comment->id"/>
            @endforeach
        </div>
    @endif

    <livewire:post-comment :post="$post"/>
</div>
