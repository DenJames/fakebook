import $ from "jquery";
import Dropzone from "dropzone";

var image_select = false;
var tag_select = false;
var modal;

$(document).ready(function () {
    $(document).on('focus', '#timeline_status_input', function () {
        $('#timeline_status_input').blur()
    });

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
                myDropzone.removeAllFiles();
                $(".no-images-uploaded").show();

                Toast.fire({
                    icon: 'success',
                    title: response.success
                })
            },
            error: function (response) {
                Toast.fire({
                    icon: 'error',
                    title: response.responseJSON.error
                })
                if (response.responseJSON.type === 'banned_word') {
                    $.get('/banned', function(response_banned) {
                        $('.posts-container').prepend(response_banned);
                        $('#post-status-top-timeline').remove();
                        $('input').remove();
                        $('textarea').remove();
                        $('button').remove();
                        $('form').remove();
                    });
                }
            }
        });
    });

    $(document).on('click', '.modal-close', function () {
        modal.hide();
    });
});

function autoResize(divElement, maxHeight) {
    divElement.style.height = 'auto'; // Temporarily shrink div to get accurate scrollHeight
    const shouldScroll = divElement.scrollHeight > maxHeight;
    divElement.style.overflowY = shouldScroll ? 'scroll' : 'hidden';
    divElement.style.height = shouldScroll ? `${maxHeight}px` : `${divElement.scrollHeight}px`;
}
