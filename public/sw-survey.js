const CACHE_NAME = 'survey-cache-v2';
const OFFLINE_URLS = ['/', '/offline'];
const API_QUEUE = [];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(OFFLINE_URLS))
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
        )
    );
});

self.addEventListener('fetch', (event) => {
    const { request } = event;
    if (request.method !== 'GET') return;
    if (request.url.includes('/survei/')) {
        event.respondWith(
            caches.match(request).then((cached) => {
                const fetchPromise = fetch(request).then((response) => {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
                    return response;
                }).catch(() => cached);
                return cached || fetchPromise;
            })
        );
    }
});

self.addEventListener('fetch', (event) => {
    const { request } = event;
    if (request.method === 'POST' && request.url.includes('/survei/')) {
        event.respondWith(
            fetch(request.clone()).catch(() => {
                return request.clone().formData().then((formData) => {
                    const entries = {};
                    formData.forEach((value, key) => entries[key] = value);
                    API_QUEUE.push({ url: request.url, entries });
                    return new Response(JSON.stringify({ status: 'queued' }), { status: 202 });
                });
            })
        );
    }
});

self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-survey') {
        event.waitUntil(
            Promise.all(API_QUEUE.splice(0).map((job) => {
                const formData = new FormData();
                Object.entries(job.entries).forEach(([k,v]) => formData.append(k,v));
                return fetch(job.url, { method: 'POST', body: formData });
            }))
        );
    }
});
