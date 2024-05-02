<x-app-layout>
    <div class="relative rounded bg-gray-100 p-4 w-full h-[calc(100vh-150px)] space-y-6">
        <div class="space-y-6 overflow-y-auto p-4 max-h-[calc(100vh-350px)]" id="messages">
            @foreach($messages as $message)
                @if($message->userIsAuthor())
                    <x-chat.sender :message="$message"/>
                @else
                    {{-- Person u chatting with --}}
                    <x-chat.receiver :message="$message"/>
                @endif
            @endforeach
        </div>

        @if($conversation)
            <div class="w-full flex flex-col absolute bottom-0 left-0 bg-gray-200 p-4">
                <input class="w-full p-2 rounded h-24 border-0" type="text" placeholder="Type a message..." id="message"
                       data-conversation_id="{{ $conversation->id }}">
                <button
                    class="rounded bg-blue-500 text-blue-100 py-2 px-4 w-full text-center mt-4 hover:bg-blue-600 transition-all duration-300"
                    id="send_message">
                    Send message
                </button>
            </div>
        @endif
    </div>

    @push('scripts')
        @vite('resources/js/chat.js')
    @endpush
</x-app-layout>
