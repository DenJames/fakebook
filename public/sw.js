self.addEventListener('install', (event) => {
    console.log('Service Worker installing.');
    // Cache assets
});

self.addEventListener('activate', (event) => {
    console.log('Service Worker activating.');
    // Perform clean up
});

self.addEventListener('fetch', (event) => {
    console.log('Fetching:', event.request.url);
    // Respond with cached assets or fetch from network
});
