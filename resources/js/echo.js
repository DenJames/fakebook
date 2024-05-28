import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
import $ from "jquery";
import {initDropdowns, initFlowbite} from "flowbite";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

const freind_sockets = {};

$(document).ready(function() {
    var user_id = $('meta[name="user-id"]').attr('content')

    $.get('/api/friends', function(response) {
        // friends is an object
        Object.keys(response.friends).forEach(function(friend_id) {
            const friend = response.friends[friend_id];
            freind_sockets[friend] = window.Echo.private('friend.' + friend + '.feeds');

            freind_sockets[friend].listen('.PostCreated', (e) => {
                $.get(e.url, function(response) {
                    $('.posts-container').prepend(response);
                    $('#post-' + e.id).find('.post-image').each(function () {
                        var rgb = getAverageRGB(this);
                        $(this).closest('.h-full').css('background-color', 'rgb(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ')');
                    });
                });
            }).listen('.PostUpdated', (e) => {
                $.get(e.url, function(response) {
                    $('#post-' + e.id).replaceWith(response);
                    $('#post-' + e.id).find('.post-image').each(function () {
                        var rgb = getAverageRGB(this);
                        // apply the style to the parrent
                        $(this).parent().css('background-color', 'rgb(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ')');
                    });
                });
            }).listen('.PostDeleted', (e) => {
                $('#post-' + e.id).remove();
            }).listen('.PostBottomUpdated', (e) => {
                $.get(e.url, function(response) {
                    $('#post-' + e.id).find('.post-bottom-content').replaceWith(response);
                });
            });
        });
    });

    window.Echo.private('me.' + user_id + '.feeds').listen('.PostCreated', (e) => {
            $.get(e.url, function(response) {
                $('.posts-container').prepend(response);

                $('#post-' + e.id).find('.post-image').each(function () {
                    var rgb = getAverageRGB(this);
                    // apply the style to the parrent
                    $(this).parent().css('background-color', 'rgb(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ')');
                });
                initDropdowns();
                initFlowbite();
            });
        }).listen('.PostUpdated', (e) => {
            $.get(e.url, function(response) {
                $('#post-' + e.id).replaceWith(response);
                $('#post-' + e.id).find('.post-image').each(function () {
                    var rgb = getAverageRGB(this);
                    // apply the style to the parrent
                    $(this).parent().css('background-color', 'rgb(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ')');
                });
                initDropdowns();
                initFlowbite();
            });
        }).listen('.PostDeleted', (e) => {
            $('#post-' + e.id).remove();
        }).listen('.PostBottomUpdated', (e) => {
            $.get(e.url, function(response) {
                $('#post-' + e.id).find('.post-bottom-content').replaceWith(response);
            });
        });
});



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



