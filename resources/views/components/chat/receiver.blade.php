@props(['message'])

<x-chat.chat-wrapper :is-sender="false">
    <div class="absolute top-2 left-2 flex gap-x-1">
        <button class="transform hover:scale-105 transition-all" onclick="toggleEdit({{ $message->id }}, true)">
            <x-icons.pencil width="w-5" height="w-5"/>
        </button>

        <button class="transform hover:scale-105 hover:text-red-600 transition-all" onclick="return deleteMessage({{ $message->id }})">
            <x-icons.trash width="w-5" height="w-5"/>
        </button>
    </div>

    <div class="flex flex-col h-full justify-content-start pl-4 w-full mt-4">
        <div class="font-bold text-lg">
            {{ $message->sender->name }}
        </div>

        <p id="message-content-{{ $message->id }}" class="message-content" style="display:block;">
            {{ $message->content }}
        </p>
        <input type="text" id="edit-message-input-{{ $message->id }}" class="edit-message-input hidden" value="{{ $message->content }}" onblur="toggleEdit({{ $message->id }}, false)" />
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

    <script>
        function toggleEdit(messageId, editMode) {
            const contentP = document.getElementById(`message-content-${messageId}`);
            const inputField = document.getElementById(`edit-message-input-${messageId}`);
            if (editMode) {
                contentP.style.display = 'none';
                inputField.style.display = 'block';
                inputField.style.color = 'black';
                inputField.style.borderRadius = '5px';
                inputField.focus();

                inputField.onkeydown = function(event) {
                    if (event.key === 'Enter') {
                        // TODO: Add save logic (backend)
                        alert('Message updated');
                        toggleEdit(messageId, false);
                    } else if (event.key === 'Escape') {
                        // Restore original content and close edit mode
                        inputField.value = contentP.textContent;
                        toggleEdit(messageId, false);
                    }
                };
            } else {
                contentP.style.display = 'block';
                inputField.style.display = 'none';
                contentP.textContent = inputField.value;
            }
        }

        function deleteMessage(messageId) {
            if (confirm('Are you sure you want to delete this message?')) {
                alert('Message deleted for ID ' + messageId);
                // TODO: Handle message deletion
            }
        }
    </script>
</x-chat.chat-wrapper>

<small class="-mt-3 text-black/50">
    Sent: {{ $message->created_at->diffForHumans() }}
</small>
