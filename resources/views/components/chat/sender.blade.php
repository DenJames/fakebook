@props(['message'])
<div class="flex flex-col items-end gap-y-4 w-full" id="message-{{ $message->id }}">
    <x-chat.chat-wrapper :is-trashed="$message->trashed()">
        @if(!$message->trashed())
            <div class="absolute top-2 right-2 flex gap-x-1">
                <button class="transform hover:scale-105 transition-all edit-message-button" data-message-id="{{ $message->id }}">
                    <x-icons.pencil width="w-5" height="w-5"/>
                </button>

                <button class="transform hover:scale-105 hover:text-red-600 transition-all delete-message-button" data-message-id="{{ $message->id }}">
                    <x-icons.trash width="w-5" height="w-5"/>
                </button>
            </div>
        @endif

        <img class="h-14 w-14 rounded-full"
             src="{{ asset($message->sender->profile_photo) }}"
             alt="{{ $message->sender->username ?? 'Unknown' }}">

        <div class="relative flex flex-col h-full justify-content-start pl-4 text-white">
            <div class="font-bold text-lg {{ $message->trashed() ? 'text-gray-700' : '' }}">
                {{ $message->sender->name }}
            </div>

            <p id="message-content-{{ $message->id }}" class="message-content" style="display:block;">
                @if($message->trashed())
                    <span class="text-gray-700">You have regretted sending a message.</span>
                @else
                    {{ $message->content }}
                @endif
            </p>
            <input type="text" id="edit-message-input-{{ $message->id }}" class="edit-message-input hidden"
                   value="{{ $message->content }}" data-message-id="{{ $message->id }}"/>
        </div>
    </x-chat.chat-wrapper>

    <small class="-mt-3 text-black/50 flex gap-x-1 items-center">
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
