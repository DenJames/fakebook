@props(['post'])

<p class="mt-1 w-full" style="overflow-wrap: break-word">
    {!! nl2br(htmlspecialchars($post->content)) !!}
</p>
