import $ from 'jquery';

$(document).ready(function() {
    var user_id = $('meta[name="user-id"]').attr('content')
    var message = $('#message');
    var conversation = message.attr('data-conversation_id');
    $('#send_message').click(function() {
        if (message.val()) {
            $.ajax({
                url: '/messages/' + conversation,
                type: 'POST',
                data: {
                    message: message.val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#message').val('');
                    $('#message').focus();
                }
            });
        }
    });


    // join the echo channel conversation.{conversation_id}
    // listen for the event message.created
    const conversation_socket = Echo.private('conversation.' + conversation + '.' + user_id);

    conversation_socket.subscribed(() => {
        console.log("Subscribed to event channel conversation." + conversation + '.' + user_id);
    }).listen('.MessageCreated', (e) => {
        $('#messages').append(e.html);
    })
});
