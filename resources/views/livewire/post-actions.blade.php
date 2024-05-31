<div class="divide-y divide-solid post-bottom-content">
    @if($post->likes()->count() || $post->comments()->count())
        <div class="grid grid-cols-2 gap-2 pb-1 pt-1">
            <div class="flex flex-row items-center gap-1">
                @if($post->likes()->count())
                    <div class="bg-blue-500 text-white rounded-full h-5 w-5 flex items-center justify-center p-1">
                        <x-icons.like height="h-4" width="w-4"/>
                    </div>
                    <span class="like-count">{{ $post->likes()->count() }}</span>
                @endif
            </div>

            <div class="flex flex-row justify-end gap-1">
                <div class="flex flex-row gap-4">
                    @if($post->comments()->count())
                        <span>{{ $post->comments()->count() }} comment{{ $post->comments()->count() == 1 ? '' : 's' }}</span>
                    @endif
                    {{-- <span>9 shars</span>--}}
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 pt-1">
        <button class="flex flex-row gap-2 items-center justify-center hover:bg-gray-300 py-1 rounded-md like-post transition-all {{ $post->hasLiked() ? 'text-blue-500' : '' }}" wire:click="like">
            <x-icons.like/>
            <span>Like post</span>
        </button>

{{--        <button class="flex flex-row gap-2 items-center justify-center hover:bg-gray-300 py-1 rounded-md rounded-l-none share- transition-all" data-post-id="{{ $post->id }}">--}}
{{--            <x-icons.share/>--}}
{{--            <span>Share</span>--}}
{{--        </button>--}}
    </div>

    <livewire:comments :post="$post" />
</div>
