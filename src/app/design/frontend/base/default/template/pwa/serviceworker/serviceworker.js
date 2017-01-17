'use strict';

const version = '<?php echo $this->getVersion() ?>';
const offlinePage = '<?php echo $this->getOfflinePageUrl() ?>';
const urlBlacklist = <?php echo json_encode($this->getOfflineUrlBlacklist()) ?>;

// Functions
// #####################################

/**
 * Add all of the static pages and assets to the cache.
 *
 * @returns {*|Promise.<TResult>}
 */
function updateStaticCache() {
    return caches.open(version)
        .then(cache => {
            return cache.addAll([
                offlinePage
            ]);
        });
}

/**
 * Delete caches that do not match the current version of the service worker.
 *
 * @returns {*|Promise.<TResult>}
 */
function clearOldCaches() {
    return caches.keys().then(keys => {
        return Promise.all(
            keys
                .filter(key => key.indexOf(version) !== 0)
                .map(key => caches.delete(key))
        );
    });
}

/**
 * Check if the given request is expecting a HTML page returned.
 *
 * @param {Request} request
 * @returns {boolean}
 */
function isHtmlRequest(request) {
    return request.headers.get('Accept').indexOf('text/html') !== -1;
}

/**
 * Check if the given URL matches any of the blacklist URL prefixes.
 *
 * @param {string} url
 * @returns {boolean}
 */
function isBlacklisted(url) {
    return urlBlacklist.filter(bl => url.indexOf(bl) == 0).length > 0;
}

/**
 * Return the response if it can be cached, null otherwise.
 *
 * @param {Response} response
 * @returns {boolean}
 */
function isCachableResponse(response) {
    return response && response.ok;
}

// Install
// #####################################

self.addEventListener('install', event => {
    event.waitUntil(
        updateStaticCache()
            .then(() => self.skipWaiting())
    );
});

// Activate
// #####################################

self.addEventListener('activate', event => {
    event.waitUntil(
        clearOldCaches()
            .then(() => self.clients.claim())
    );
});

// Fetch
// #####################################

self.addEventListener('fetch', event => {
    let request = event.request;

    if (request.method !== 'GET') {
        // Only process GET request, unless offline and expecting a HTML page, then show the offline page instead
        if (!navigator.onLine && isHtmlRequest(request)) {
            return event.respondWith(caches.match(offlinePage));
        }
        return;
    }

    if (isHtmlRequest(request)) {
        // For HTML requests, fetch from the network first, otherwise fall back to cache
        event.respondWith(
            fetch(request)
                .then(response => {
                    if (isCachableResponse(response) && !isBlacklisted(response.url)) {
                        let copy = response.clone();
                        caches.open(version).then(cache => cache.put(request, copy));
                    }
                    return response;
                })
                .catch(() => {
                    return caches.match(request)
                        .then(response => {
                            if (!response && request.mode == 'navigate') {
                                return caches.match(offlinePage);
                            }
                            return response;
                        });
                })
        );
    } else {
        // For asset requests, get from cache, otherwise fetch from the network
        event.respondWith(
            caches.match(request)
                .then(response => {
                    return response || fetch(request)
                            .then(response => {
                                if (isCachableResponse(response)) {
                                    let copy = response.clone();
                                    caches.open(version).then(cache => cache.put(request, copy));
                                }
                                return response;
                            })
                })
        );
    }
});

