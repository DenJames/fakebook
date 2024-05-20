<div id="message-{{ $message->id }}">
    <x-chat.chat-wrapper :is-sender="false" :is-trashed="$message->trashed()">
        <div class="flex flex-col h-full justify-content-start pl-4 w-full">
            <div class="font-bold text-lg">
                {{ $message->sender->name }}
            </div>

            <p id="message-content-{{ $message->id }}" class="message-content" style="display:block;">
                @if($message->trashed())
                    <span class="text-neutral-500">{{ $message->sender->name }} have regretted sending a message.</span>
                @else
                    {{ $message->content }}
                @endif
            </p>
        </div>

        <div>
            <div class="flex justify-end w-24">
                <div>
                    <img class="h-14 w-14 rounded-full"
                         src="{{ asset($message->sender->profile_photo) }}"
                         alt="{{ $message->sender->username ?? 'Unknown' }}">
                </div>
            </div>
        </div>
    </x-chat.chat-wrapper>

    <small class="text-black/50 flex gap-x-1 items-center">
        Sent: {{ $message->created_at->diffForHumans() }}

        @if($message->hasBeenEdited())
            <span class="text-black/50 text-xs" data-tooltip-target="message-{{ $message->id }}-edited">(Edited)</span>
            <div id="message-{{ $message->id }}-edited" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Edited: {{ $message->edited_at->diffForHumans() }}
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        @endif

        @if($message->trashed())
            <span class="text-black/50 text-xs" data-tooltip-target="message-{{ $message->id }}-deleted">(Deleted)</span>
            <div id="message-{{ $message->id }}-deleted" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Deleted: {{ $message->deleted_at->diffForHumans() }}
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        @endif

        @if($message->hasBeenRead())
            <x-icons.checkmark width="w-4" height="h-4" />
        @endif
    </small>
</div>
