@props(['post'])

@if($post->images->isNotEmpty())
    @switch($post->images->count())
        @case(1)
            <x-post.images.one :images="$post->images"/>
        @break
        @case(2)
            <x-post.images.two :images="$post->images"/>
        @break
        @case(3)
            <x-post.images.three :images="$post->images"/>
        @break
        @case(4)
            <x-post.images.four :images="$post->images"/>
        @break
        @default
            <x-post.images.five :images="$post->images"/>
        @break

    @endswitch
@endif
