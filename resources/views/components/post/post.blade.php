@props(['post'])

@if ($post->authorizedToSee())
    <div class="rounded-lg bg-gray-100 mt-3" id="post-{{ $post->id }}">
        <div class="{{ $post->images->isNotEmpty() ? 'p-3' : 'pt-3 px-3' }}">
            <x-post.top :post="$post"/>
            <x-post.content :post="$post"/>
        </div>

        <x-post.image :post="$post"/>

        <div class="divide-y px-3 pb-1">
            <x-post.bottom :post="$post"/>
        </div>
    </div>
@endif
