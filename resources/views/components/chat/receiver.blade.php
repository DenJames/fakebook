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
                         src="{{ asset($message->sender->profile_photo) }}"
                         alt="{{ $message->sender->username ?? 'Unknown' }}">
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
