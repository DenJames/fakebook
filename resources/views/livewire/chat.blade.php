<div class="relative rounded bg-gray-100 p-4 w-full h-[calc(100vh-150px)] space-y-6 chat-container"
     data-conversation-id="{{ $conversationId }}">
    <div class="space-y-6 overflow-y-auto p-4 max-h-[calc(100vh-350px)]" id="messages">
        @if($this->hasMorePages())
            <div class="w-full flex justify-center">
                <button class="bg-blue-600 py-2 px-4 rounded-full text-white"
                        wire:click="incrementPage"
                        wire:loading.attr="disabled">
                    Load older messages
                </button>
            </div>
        @endif

        @for ($chunk = $page - 1; $chunk >= 0; $chunk--)
            <livewire:chat-messages :ids="$this->chunks[$chunk]" :key="$page - 1" :conversation="$conversation"/>
        @endfor
    </div>


    @if($conversation)
        <form wire:submit.prevent="sendMessage">
            <input
                class="w-full p-2 rounded h-24 border-0"
                type="text"
                placeholder="Type a message..."
                wire:model.debounce.500ms="messageText"
                wire:keydown.enter="sendMessage"
                id="message-input"
            >
            <button
                class="rounded bg-blue-500 text-blue-100 py-2 px-4 w-full text-center mt-4 hover:bg-blue-600 transition-all duration-300"
            >
                Send message
            </button>
        </form>
    @endif
</div>

@script
<script>
    let pageHasIncremented = false;
    window.addEventListener('DOMContentLoaded', function () {
        const messageContainer = document.getElementById('messages');

        function scrollToBottom() {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        window.addEventListener('load', () => {
            if (pageHasIncremented) {
                return;
            }

            requestAnimationFrame(() => {
                scrollToBottom();
            });
        });


        //Livewire events
        $wire.on('page-incremented', () => {
            pageHasIncremented = true;
        });

        $wire.on('message-posted', () => {
            if (pageHasIncremented) {
                return;
            }

            requestAnimationFrame(() => {
                scrollToBottom();
            });
        });

        $wire.on('message-sent', () => {
            document.getElementById('message-input').value = '';
        });
    });
</script>
@endscript




