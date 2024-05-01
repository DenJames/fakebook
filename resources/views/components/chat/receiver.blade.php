<x-chat.chat-wrapper :is-sender="false">
    <div class="flex flex-col h-full justify-content-start pl-4 w-full">
        <div class="font-bold text-lg">
            {{ $username }}
        </div>

        <p>{{ $slot }}</p>
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
