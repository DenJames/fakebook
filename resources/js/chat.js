import $ from 'jquery';

$(document).ready(function() {
    var user_id = $('meta[name="user-id"]').attr('content')
    var message = $('#message');
    var conversation = message.attr('data-conversation_id');
    (function() {
        var messages = $('#messages');
        messages.scrollTop(messages.prop('scrollHeight'));
    })();

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
        var messages = $('#messages');
        var scrollPosition = messages.scrollTop();
        var scrollHeight = messages[0].scrollHeight;
        $('#messages').append(e.html);
        if (e.user_id != user_id) {
            $.ajax({
                url: '/messages/' + e.message_id + '/read',
                type: 'PATCH',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                }
            });

            if (scrollPosition+messages.outerHeight()+20 >= scrollHeight) {
                setTimeout(function() {
                    messages.scrollTop(messages[0].scrollHeight);
                }, 0);
            }
        } else {
            (function() {
                var messages = $('#messages');
                messages.scrollTop(messages.prop('scrollHeight'));
            })();
        }
    }).listen('.MessageEdited', (e) => {
        $(document).find(`#message-${e.message_id}`).replaceWith(e.html);
    }).listen('.MessageDeleted', (e) => {
        $(document).find(`#message-${e.message_id}`).replaceWith(e.html);
    }).listen('.MessageRead', (e) => {
        $(document).find(`#message-${e.message_id}`).find('small').get(0).append($(e.html).get(0));
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
