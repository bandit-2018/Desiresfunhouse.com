/*
 * Author:        Pierre-Henry Soria <hello@ph7builder.com>
 * Copyright:     (c) 2018-2019, Pierre-Henry Soria. All Rights Reserved.
 * License:       MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 */

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('[$url_root]service-worker.js', {
        scope: '[$url_relative]'
    }).then(function (registration) {
        console.log('Service Worker registered on scope: ' + registration.scope);
    }).catch(function (error) {
        console.log('Service Worker registration has failed: ', error);
    });

    navigator.serviceWorker.getRegistrations().then(function (registrations) {
        registrations.forEach(function (registration) {
            if (!isBaseUrl(registration.scope)) {
                return registration.unregister();
            }
        });
    });
}

function isBaseUrl(scope) {
    const url = new URL(scope);
    return url.pathname === '[$url_relative]';
}
