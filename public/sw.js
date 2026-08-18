// public/sw.js
self.addEventListener('install', e => self.skipWaiting());
self.addEventListener('activate', e => e.waitUntil(clients.claim()));

self.addEventListener('push', function (e) {
    const dados = e.data ? e.data.json() : {};

    const titulo = dados.titulo || 'Barbearia';
    const opcoes = {
        body: dados.corpo || 'Nova notificação',
        icon: '/barbearia/public/assets/images/icon.png',
        badge: '/barbearia/public/assets/images/icon.png',
        data: { url: dados.url || '/barbearia/' },
    };

    e.waitUntil(self.ServiceWorkerRegistration.showNotification(titulo, opcoes));

    self.addEventListener('notificationclick', function (e) {
        e.notification.close();
        e.waitUntil(clients.openWindow(e.notification.data.url));
    });
})