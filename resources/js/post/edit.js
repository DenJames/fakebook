import $ from "jquery";
import Dropzone from "dropzone";
import {Modal} from "flowbite";

var modal;
let myPostDropzone;

// Disable auto discover for all elements:
Dropzone.autoDiscover = false;
let remainingImages = [];

$(document).ready(function () {

    $(document).on('focus', '#timeline_status_input', function () {
        $('#timeline_status_input').blur()
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

                myPostDropzone = new Dropzone("#post-update", {
                    url: "/posts/"+post_id+"/image", // replace with your upload URL
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    paramName: "file", // The name that will be used to transfer the file
                    maxFilesize: 2, // MB
                    addRemoveLinks: true,
                    autoProcessQueue: true,
                    maxFiles: 100,
                    thumbnailMethod: 'contain',
                    dictRemoveFile: 'Remove',
                    dictCancelUpload: 'Cancel',
                    dictDefaultMessage: '',
                    previewsContainer: ".image-preview-div-updated",
                    previewTemplate: $('#post-update').find('.preview-template').html(),
                    init: function () {
                        this.on("addedfile", function () {
                            $("#post-update").find(".no-images-uploaded").hide();
                        });

                        this.on("removedfile", function () {
                            if (this.files.length === 0) {
                                $("#post-update").find(".no-images-uploaded").show();
                            }
                        });
                    }
                });

                myPostDropzone.on("removedfile", function(file) {
                    if (file.id) {
                        $.ajax({
                            url: '/posts/image/' + file.id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        });
                    }
                });

                myPostDropzone.on("success", function(file, response) {
                    // Add the id to the file object
                    file.id = response.id;
                });

                // Create a Dropzone for the #image-form element:
                $("#post-update").find(".update-post-images").click(function () {
                    myPostDropzone.hiddenFileInput.click();
                });

                $.get('/posts/' + post_id + '/images', function(data) {
                    // For each image
                    $.each(data, function(key, value) {

                        var mockFile = { name: value.name, size: value.size, id: value.id };

                        // Add the file to the Dropzone instance
                        myPostDropzone.emit("addedfile", mockFile);

                        // Create a thumbnail
                        myPostDropzone.emit("thumbnail", mockFile, value.url);

                        // Signal that the file is uploaded
                        myPostDropzone.emit("complete", mockFile);

                        remainingImages.push(value.id);
                    });
                });
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

                $('#timeline_status_input').val('');

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
                        modal.hide();
                        $('#timeline_status_input').val('');
                        $('#post-status-top-timeline').remove();
                        $('form').remove();
                        $('input').remove();
                        $('textarea').remove();
                        $('button').remove();
                        $('.posts-container').prepend(response_banned);
                    });
                }
            }
        });
    });

    $(document).on('click', '.modal-close', function () {
        modal.hide();
        $('#timeline_status_input').val('');
    });
});

function autoResize(divElement, maxHeight) {
    divElement.style.height = 'auto'; // Temporarily shrink div to get accurate scrollHeight
    const shouldScroll = divElement.scrollHeight > maxHeight;
    divElement.style.overflowY = shouldScroll ? 'scroll' : 'hidden';
    divElement.style.height = shouldScroll ? `${maxHeight}px` : `${divElement.scrollHeight}px`;
}
