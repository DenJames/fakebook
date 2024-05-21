import {initFlowbite, initDropdowns} from 'flowbite'
import $ from "jquery";
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


initFlowbite();
initDropdowns();


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

$(document).ready(function() {

});

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
        .then((registration) => {
            console.log('Service Worker registered with scope:', registration.scope);
        })
        .catch((error) => {
            console.log('Service Worker registration failed:', error);
        });
}

let deferredPrompt;

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    deferredPrompt = event;

    // Check if the app has already been installed
    if (!localStorage.getItem('pwaInstalled')) {
        showAddToHomeScreenButton();
    }
});

function showAddToHomeScreenButton() {
    const addToHomeScreenButton = document.getElementById('pwa-banner');
    addToHomeScreenButton.style.display = 'block';

    document.getElementById('close-pwa-button').addEventListener('click', () => {
        addToHomeScreenButton.style.display = 'none';
    });

    document.getElementById('pwa-install-button').addEventListener('click', () => {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            deferredPrompt = null;
        });
    });
}

window.addEventListener('appinstalled', () => {
    localStorage.setItem('pwaInstalled', 'true');

    // Hide the A2HS button if it's being displayed
    const addToHomeScreenButton = document.getElementById('pwa-banner');
    if (addToHomeScreenButton) {
        addToHomeScreenButton.style.display = 'none';
    }
});
