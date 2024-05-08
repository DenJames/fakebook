import $ from "jquery";
import Dropzone from "dropzone";
import {initFlowbite, initDropdowns, Modal} from "flowbite";

var image_select = false;
var tag_select = false;
var modal;

$(document).ready(function () {
    var user_id = $('meta[name="user-id"]').attr('content')

    $(document).on('focus', '#timeline_status_input', function () {
        $('#timeline_status_input').blur()
    });

    /*$(document).on('blur', '#timeline-textarea-status', function () {
        $('#timeline_status_input').val($('#timeline-textarea-status').val().replaceAll('\n', ' '));
    });*/

    $(document).on('click', '.timeline-image-button', function () {
        if (image_select) {
            $(this).removeClass('bg-gray-800');
            $('.timeline-image-container').addClass('hidden');
            image_select = false;
        } else {
            $(this).addClass('bg-gray-800');
            $('.timeline-image-container').removeClass('hidden');
            image_select = true;
        }
    });

    $(document).on('click', '.timeline-tag-button', function () {
        if (tag_select) {
            $(this).removeClass('bg-gray-800');
            // $('.timeline-image-container').addClass('hidden');
            tag_select = false;
        } else {
            $(this).addClass('bg-gray-800');
            // $('.timeline-image-container').removeClass('hidden');
            tag_select = true;
        }
    });

    $(document).on('click', '.edit-post', function () {
        let post_id = $(this).attr('data-post-id');

        $.ajax({
            url: '/posts/' + post_id + '/edit',
            type: 'GET',
            success: function (response) {
                $(document).find('.edit-post-modal').replaceWith(response);

                const $targetEl = document.getElementById('post-edit-' + post_id);

                modal = new Modal($targetEl);

                modal.show();
            },
            error: function (response) {
                alert(response);
            }
        });
    });

    $(document).on('click', '.delete-post', function () {
        let post_id = $(this).attr('data-post-id');

        if (!confirm('Are you sure you want to delete this item?')) {
            return false;
        }

        $.ajax({
            url: '/posts/' + post_id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // $('#post-' + post_id).remove();
            },
            error: function (response) {
                alert(response);
            }
        });
    });

    let deviceHeight = window.innerHeight / 2.4;
    const divElement = document.getElementById('timeline-textarea-status');
    divElement.addEventListener('input', () => autoResize(divElement, deviceHeight));
    // auto rezise on enter press inside the div timeline-textarea-status
    divElement.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            autoResize(divElement, deviceHeight);
        }
    });

    // Disable auto discover for all elements:
    Dropzone.autoDiscover = false;

    // Create a Dropzone for the #image-form element:
    let myDropzone = new Dropzone("#image-form", {
        url: "/posts/store/image", // replace with your upload URL
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 2, // MB
        addRemoveLinks: true,
        autoProcessQueue: false,
        maxFiles: 100,
        thumbnailMethod: 'contain',
        dictRemoveFile: 'Remove',
        dictCancelUpload: 'Cancel',
        dictDefaultMessage: '',
        previewsContainer: ".image-preview-div",
        previewTemplate: document.querySelector('.preview-template').innerHTML,
        init: function () {
            this.on("addedfile", function () {
                $(".no-images-uploaded").hide();
            });

            this.on("removedfile", function () {
                if (this.files.length === 0) {
                    $(".no-images-uploaded").show();
                }
            });
        }
    });

    $(".no-images-uploaded").click(function () {
        myDropzone.hiddenFileInput.click();
    });


    // When the form is submitted
    $(document).on('submit', '#image-form', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        myDropzone.files.forEach(function (file) {
            formData.append("images[]", file);
        });

        formData.append('content', document.getElementById('timeline-textarea-status').innerHTML.replace('contenteditable="true"', '').replace('tiptap', ''));

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                myDropzone.removeAllFiles();
                $(".no-images-uploaded").show();
            },
            error: function (response) {
                console.log(response);
            }
        });
    });

    $(document).on('submit', '#post-update', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        formData.append('content', document.getElementById('timeline-textarea-status-edit').innerHTML.replace('contenteditable="true"', '').replace('tiptap', ''));
        formData.append('_method', 'PATCH');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // $('#post-' + response.id).replaceWith($(response.view));
                modal.hide();
                /*$('#post-' + response.id).find('.post-image').each(function () {
                    var rgb = getAverageRGB(this);
                    // apply the style to the parrent
                    $(this).parent().css('background-color', 'rgb(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ')');
                });
                initDropdowns();
                initFlowbite();*/
                $('#timeline_status_input').val('');
            },
            error: function (response) {
                console.log(response);
            }
        });
    });

    /*$(document).on('click', '#dropdownMenuIconHorizontalButton', function () {
        $('#dropdownDotsHorizontal').toggleClass('hidden');
    });*/

    // loop all elements with class post-image
    $('.post-image').each(function () {
        var rgb = getAverageRGB(this);
        // apply the style to the parrent
        $(this).parent().css('background-color', 'rgb(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ')');
    });

    $(document).on('click', '.modal-close', function () {
        modal.hide();
    });

    $(document).on('click', '.like-post', function () {
        var post_id = $(this).attr('data-post-id');

        sendAjax('/posts/' + post_id + '/like', 'POST', {});
    });

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
        success: function (response) {
        },
        error: function (response) {
            console.log(response);
        }
    });
}


function autoResize(divElement, maxHeight) {
    console.log("Update");
    divElement.style.height = 'auto'; // Temporarily shrink div to get accurate scrollHeight
    const shouldScroll = divElement.scrollHeight > maxHeight;
    divElement.style.overflowY = shouldScroll ? 'scroll' : 'hidden';
    divElement.style.height = shouldScroll ? `${maxHeight}px` : `${divElement.scrollHeight}px`;
}

function getAverageRGB(imgEl) {

    var blockSize = 5, // only visit every 5 pixels
        defaultRGB = {
            r: 0,
            g: 0,
            b: 0
        }, // for non-supporting envs
        canvas = document.createElement('canvas'),
        context = canvas.getContext && canvas.getContext('2d'),
        data, width, height,
        i = -4,
        length,
        rgb = {
            r: 0,
            g: 0,
            b: 0
        },
        count = 0;

    if (!context) {
        return defaultRGB;
    }

    height = canvas.height = imgEl.naturalHeight || imgEl.offsetHeight || imgEl.height;
    width = canvas.width = imgEl.naturalWidth || imgEl.offsetWidth || imgEl.width;

    context.drawImage(imgEl, 0, 0);

    try {
        data = context.getImageData(0, 0, width, height);
    } catch (e) {
        /* security error, img on diff domain */
        return defaultRGB;
    }

    length = data.data.length;

    while ((i += blockSize * 4) < length) {
        ++count;
        rgb.r += data.data[i];
        rgb.g += data.data[i + 1];
        rgb.b += data.data[i + 2];
    }

    // ~~ used to floor values
    rgb.r = ~~(rgb.r / count);
    rgb.g = ~~(rgb.g / count);
    rgb.b = ~~(rgb.b / count);

    return rgb;

}
