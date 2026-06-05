const CACHE_NAME = 'mam-portal-cache-v2';
const urlsToCache = [
  '/assets/img/logo.png',
  '/assets/img/logo-3d.png',
  'https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
});

self.addEventListener('fetch', event => {
  const request = event.request;
  const url = new URL(request.url);

  // Cache-First strategy for static assets
  if (
    url.pathname.startsWith('/assets/') ||
    url.pathname.startsWith('/build/') ||
    url.hostname.includes('fonts.googleapis.com') ||
    url.hostname.includes('fonts.gstatic.com')
  ) {
    event.respondWith(
      caches.match(request).then(response => {
        return response || fetch(request).then(networkResponse => {
          if (networkResponse && networkResponse.status === 200 && request.method === 'GET') {
            const responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(request, responseToCache);
            });
          }
          return networkResponse;
        });
      })
    );
  } else {
    // Network-First strategy for dynamic pages (like /apps, /apps/artikel, etc.)
    event.respondWith(
      fetch(request)
        .then(networkResponse => {
          // If it's a successful GET request to our own site, cache it as fallback
          if (request.method === 'GET' && networkResponse.status === 200 && url.origin === self.location.origin) {
            const responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(request, responseToCache);
            });
          }
          return networkResponse;
        })
        .catch(() => {
          // Fallback to cache if network is down
          return caches.match(request);
        })
    );
  }
});
