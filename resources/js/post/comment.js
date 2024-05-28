import $ from "jquery";

$(document).ready(function () {
    $(document).on('keydown', '.post-comment-input', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            var post_id = $(this).attr('data-post-id');
            var comment = $(this).val();

            sendAjax('/posts/' + post_id + '/comment', 'POST', {comment: comment});
        }
    });

    $(document).on('click', '.like-comment', function () {
        var comment_id = $(this).attr('data-comment-id');

        sendAjax('/comments/' + comment_id + '/like', 'POST', {});
    });

    $(document).on('click', '.comment-post', function () {
        var post_id = $(this).attr('data-post-id');
        $(".post-"+post_id+"-comment-section").toggleClass("hidden");
        $(this).toggleClass("text-blue-500");
    });

    $(document).on('click', '.delete-comment', function () {
        var comment_id = $(this).attr('data-comment-id');

        sendAjax('/comments/' + comment_id, 'DELETE', {});
    });
});

function sendAjax(url, method, data) {
    data._token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: method,
        data: data,
    });
}
