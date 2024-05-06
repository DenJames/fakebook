@props(['post'])

<div class="rounded-lg bg-gray-100 mt-3" id="post-{{ $post->id }}">
    <div class="p-3">
        <x-post.top :post="$post"/>
        <x-post.content :post="$post"/>
    </div>
    <x-post.image :post="$post"/>
    <div class="p-3">
{{--        Comment addon--}}
    </div>
</div>
