const CACHE_NAME = 'tibra-sare-v3';
const ASSETS_TO_CACHE = [
    '/',
    '/offline.html',
    '/images/IconTS.png',
    '/images/hero.png'
];

self.addEventListener('install', (event) => {
    self.skipWaiting(); // Force new Service Worker to activate immediately
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
});

self.addEventListener('fetch', (event) => {
    // Hanya tangani request yang mengarah ke origin yang sama (Bypass external API/Fonts/Analytics)
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }
    // Network-First strategy for navigation requests (HTML pages)
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match('/offline.html');
            })
        );
        return;
    }

    // Cache-First untuk gambar, stylesheet, dan font
    const isStaticAsset = event.request.url.match(/\.(css|js|png|jpg|jpeg|svg|woff2|webp)$/);
    if (isStaticAsset) {
        event.respondWith(
            caches.match(event.request).then((response) => {
                return response || fetch(event.request).then((networkResponse) => {
                    return caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, networkResponse.clone());
                        return networkResponse;
                    });
                });
            })
        );
        return;
    }

    // Default ke Network untuk request API dan lain-lain
    event.respondWith(fetch(event.request));
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        clients.claim().then(() => {
            return caches.keys().then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cache) => {
                        if (cache !== CACHE_NAME) {
                            return caches.delete(cache);
                        }
                    })
                );
            });
        })
    );
});
