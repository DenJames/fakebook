<div>
    <x-content.card content-classes="!items-start max-h-[650px] overflow-y-auto">
        <div class="flex flex-col gap-4 w-full">
            <p class="font-bold">Replies</p>
            @foreach($ticket->replies->reverse() as $reply)
                <div>
                    <div @class([
                            'rounded bg-gray-100 w-full relative flex p-4 text-black',
                            '!bg-blue-600 text-white flex-col items-start' => !$reply->isAuthor(),
                            '!bg-yellow-300 !text-black' => !$reply->isAuthor() && ($reply->user->hasRole('admin') && Auth::user()->hasRole('admin'))
                        ])>
                        <div class="flex gap-4">
                            <img src="{{ asset($reply->user->profile_photo) }}" alt=""
                                 class="w-10 h-10 rounded-full">
                            <article>
                                {!! $reply->content !!}
                            </article>
                        </div>
                    </div>

                    <div class="w-full flex justify-end mt-1">
                        <small>Posted by: {{ $reply->user->name }}
                            - {{ $reply->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </x-content.card>
</div>
