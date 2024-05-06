@props(['post'])

<p class="mt-1 w-full break-all">
    {!! nl2br(htmlspecialchars($post->content)) !!}
</p>
