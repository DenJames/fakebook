<x-app-layout>
    <div class="relative rounded bg-gray-100 p-4 w-full min-h-screen space-y-6">
        <div class="space-y-6" id="messages">
            @foreach($conversation->messages()->orderByDesc('created_at')->limit(30)->get()->sortBy('created_at') as $message)
                @if($message->user_id == auth()->id())
                    <div class="flex flex-col items-end gap-y-4 w-full">
                        <x-chat.sender>
                            <x-slot:username>
                                {{ $message->sender->name }}
                            </x-slot:username>
                            {{ $message->content }}
                        </x-chat.sender>
                    </div>
                @else
                    {{-- Person u chatting with --}}
                    <x-chat.receiver>
                        <x-slot:username>
                            {{ $message->sender->name }}
                        </x-slot:username>
                        {{ $message->content }}
                    </x-chat.receiver>
                @endif
            @endforeach
        </div>

        <div class="w-[calc(100%-32px)] flex flex-col absolute bottom-5">
            <input class="w-full p-2 rounded h-24 border-0" type="text" placeholder="Type a message..." id="message" data-conversation_id="{{ $conversation->id }}">
            <button
                class="rounded bg-blue-500 text-blue-100 py-2 px-4 w-full text-center mt-4 hover:bg-blue-600 transition-all duration-300" id="send_message">
                Send message
            </button>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/chat.js')
    @endpush
</x-app-layout>
