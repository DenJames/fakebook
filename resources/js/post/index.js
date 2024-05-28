import $ from "jquery";

var modal;

$(document).ready(function () {
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
                Toast.fire({
                    icon: 'success',
                    title: response.success
                })
            },
            error: function (response) {
                alert(response);
            }
        });
    });

    // loop all elements with class post-image
    $('.post-image').each(function () {
        var rgb = getAverageRGB(this);
        // apply the style to the parrent
        $(this).parent().css('background-color', 'rgb(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ')');
    });

    $(document).on('click', '.like-post', function () {
        var post_id = $(this).attr('data-post-id');

        sendAjax('/posts/' + post_id + '/like', 'POST', {});
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
