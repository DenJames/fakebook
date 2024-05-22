@props(['images'])

<div class="flex flex-col gap-0.5 w-full">
    <div class="flex flex-row gap-0.5">
        @for($i = 0; $i < 2; $i++)
            <div class="h-auto overflow-hidden max-h-[380px]">
                <a data-fslightbox="post-{{$images[$i]->post_id}}" href="{{  asset($images[$i]->url) }}" class="w-full h-full">
                    <div class="flex items-center justify-center h-full w-full">
                        <img src="{{  asset($images[$i]->url) }}" alt="{{ $images[$i]->name }}" class="post-image">
                    </div>
                </a>
            </div>
        @endfor
    </div>

    <div class="flex flex-row gap-0.5">
        @for($i = 2; $i < 5; $i++)
            <div class="h-auto overflow-hidden max-h-[380px] w-1/3 {{ $i == 5 - 1 ? 'relative' : '' }}">
                <a data-fslightbox="post-{{$images[$i]->post_id}}" href="{{  asset($images[$i]->url) }}" class="w-full h-full">
                    <div class="flex items-center justify-center h-full w-full">
                        <img src="{{  asset($images[$i]->url) }}" alt="{{ $images[$i]->name }}" class="post-image">
                    </div>
                </a>

                @if($i == 5 - 1 && (count($images)-5) > 0)
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-20 text-white text-3xl font-bold">
                        +{{ (count($images)-5) }}
                    </div>
                @endif
            </div>
        @endfor
    </div>
</div>
