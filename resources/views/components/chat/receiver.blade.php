@props(['message'])
<div id="message-{{ $message->id }}">
    <x-chat.chat-wrapper :is-sender="false">
        <div class="flex flex-col h-full justify-content-start pl-4 w-full">
            <div class="font-bold text-lg">
                {{ $message->sender->name }}
            </div>

            <p id="message-content-{{ $message->id }}" class="message-content" style="display:block;">
                {{ $message->content }}
            </p>
        </div>

        <div>
            <div class="flex justify-end w-24">
                <div>
                    <img class="h-14 w-14 rounded-full"
                         src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                         alt="">
                </div>
            </div>
        </div>
    </x-chat.chat-wrapper>

    <small class="text-black/50 flex gap-x-1 items-center">
        Sent: {{ $message->created_at->diffForHumans() }}

        @if($message->hasBeenEdited())
            <span class="text-black/50 text-xs">(Edited)</span>
        @endif

        @if($message->hasBeenRead())
            <x-icons.checkmark width="w-4" height="h-4" />
        @endif
    </small>
</div>
