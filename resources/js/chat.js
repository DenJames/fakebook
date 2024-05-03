import $ from 'jquery';

$(document).ready(function() {
    var user_id = $('meta[name="user-id"]').attr('content')
    var message = $('#message');
    var conversation = message.attr('data-conversation_id');
    $('#send_message').click(function() {
        if (message.val()) {
            var msg = message.val();
            $('#message').val('');
            $('#message').focus();
            $.ajax({
                url: '/messages/' + conversation,
                type: 'POST',
                data: {
                    message: msg,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                }
            });
        }
    });

    $(document).on('click', '.edit-message-button', function () {
        toggleEdit($(this).attr('data-message-id'), true);
    });

    $(document).on('blur', '.edit-message-input', function () {
        toggleEdit($(this).attr('data-message-id'), false);
    });

    $(document).on('click', '.delete-message-button', function () {
        if (!confirm('Are you sure you want to delete this message?')) {
            return;
        }

        $.ajax({
            url: '/messages/' + $(this).attr('data-message-id'),
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
            }
        });
    });


    // join the echo channel conversation.{conversation_id}
    // listen for the event message.created
    const conversation_socket = Echo.private('conversation.' + conversation + '.' + user_id);

    conversation_socket.subscribed(() => {
        console.log("Subscribed to event channel conversation." + conversation + '.' + user_id);
    }).listen('.MessageCreated', (e) => {
        $('#messages').append(e.html);
    }).listen('.MessageEdited', (e) => {
        $(document).find(`#message-${e.message_id}`).replaceWith(e.html);
    }).listen('.MessageDeleted', (e) => {
        $(document).find(`#message-${e.message_id}`).remove();
    })
});

function toggleEdit(messageId, editMode) {
    const contentP = document.getElementById(`message-content-${messageId}`);
    const inputField = document.getElementById(`edit-message-input-${messageId}`);
    if (editMode) {
        contentP.style.display = 'none';
        inputField.style.display = 'block';
        inputField.style.color = 'black';
        inputField.style.borderRadius = '5px';
        inputField.focus();

        inputField.onkeydown = function (event) {
            if (event.key === 'Enter') {
                if (contentP.textContent.trim() !== inputField.value) {
                    // TODO: Add save logic (backend)
                    $.ajax({
                        url: '/messages/' + messageId,
                        type: 'PATCH',
                        data: {
                            message: inputField.value,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                        }
                    });
                    // alert('Message updated ID: ' + messageId);
                }
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
        // contentP.textContent = inputField.value;
    }
}

function deleteMessage(messageId) {
    if (confirm('Are you sure you want to delete this message?')) {
        alert('Message deleted for ID ' + messageId);
        // TODO: Handle message deletion
    }
}
