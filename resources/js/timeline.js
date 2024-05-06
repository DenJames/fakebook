import $ from "jquery";
import Dropzone from "dropzone";

var image_select = false;
var tag_select = false;
let myDropzone;

$(document).ready(function() {
    var user_id = $('meta[name="user-id"]').attr('content')

    $(document).on('focus', '#timeline_status_input', function () {
        $('#timeline_status_input').blur()
    });

    $(document).on('blur', '#timeline-textarea-status', function () {
        $('#timeline_status_input').val ($('#timeline-textarea-status').val().replaceAll('\n', ' '));
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

    let deviceHeight = window.innerHeight/2.4;
    const textarea = document.getElementById('timeline-textarea-status');
    textarea.addEventListener('input', () => autoResize(textarea, deviceHeight)); // 200px max height

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
        init: function() {
            this.on("addedfile", function() {
                $(".no-images-uploaded").hide();
            });

            this.on("removedfile", function() {
                if (this.files.length === 0) {
                    $(".no-images-uploaded").show();
                }
            });
        }
    });

    $(".no-images-uploaded").click(function() {
        myDropzone.hiddenFileInput.click();
    });


    // When the form is submitted
    $(document).on('submit', '#image-form', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        myDropzone.files.forEach(function(file) {
            formData.append("images[]", file);
        });

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
});


function autoResize(textarea, maxHeight) {
    textarea.style.height = 'auto'; // Temporarily shrink textarea to get accurate scrollHeight
    const shouldScroll = textarea.scrollHeight > maxHeight;
    textarea.style.overflowY = shouldScroll ? 'scroll' : 'hidden';
    textarea.style.height = shouldScroll ? `${maxHeight}px` : `${textarea.scrollHeight}px`;
}
