@props(['message'])
<div class="flex flex-col items-end gap-y-4 w-full" id="message-{{ $message->id }}">
    <x-chat.chat-wrapper>
        <div class="absolute top-2 right-2 flex gap-x-1">
            <button class="transform hover:scale-105 transition-all edit-message-button" data-message-id="{{ $message->id }}">
                <x-icons.pencil width="w-5" height="w-5"/>
            </button>

            <button class="transform hover:scale-105 hover:text-red-600 transition-all delete-message-button" data-message-id="{{ $message->id }}">
                <x-icons.trash width="w-5" height="w-5"/>
            </button>
        </div>

        <img class="h-14 w-14 rounded-full bg-gray-50"
             src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
             alt="">

        <div class="relative flex flex-col h-full justify-content-start pl-4 text-white">
            <div class="font-bold text-lg">
                {{ $message->sender->name }}
            </div>

            <p id="message-content-{{ $message->id }}" class="message-content" style="display:block;">
                {{ $message->content }}
            </p>
            <input type="text" id="edit-message-input-{{ $message->id }}" class="edit-message-input hidden"
                   value="{{ $message->content }}" data-message-id="{{ $message->id }}"/>
        </div>
    </x-chat.chat-wrapper>

    <small class="-mt-3 text-black/50 flex gap-x-1 items-center">
        Sent: {{ $message->created_at->diffForHumans() }}

        @if($message->hasBeenEdited())
            <span class="text-black/50 text-xs">(Edited)</span>
        @endif

        @if($message->hasBeenRead())
            <x-icons.checkmark width="w-4" height="h-4" />
        @endif
    </small>
</div>
