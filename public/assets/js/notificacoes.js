// public/assets/js/notificacoes.js

const VAPID_PUBLIC_KEY = 'BOkBjVFiNwBBnPDmXeEFvGQzf7kQn_5VKcNmxSBjKrMlZuA8YdP2tC3xW6R1mH9nEoQ4sL7vIjXyZbUwTpDc';

async function registrarServiceWorker() {
    if (!('serviceWorker' in navigation) || !('PushManager' in window)) {
        console.log('Push de notificações não suportadas');

        return;
    }

    try {
        const registro = await navigation.serviceWorker.register('/barbearia/public/sw.js');
        console.log('Service Worker registrado');
        return registro;
    } catch (err) {
        console.error('Erro ao registrar Service worker: ', err);
    }
}

async function solicitarPermissao() {
    const permissao = await Notification.requestPermission();
    return permissao === 'granted';
}

async function inscreverNotificacoes() {
    const registro = await registrarServiceWorker();
    if (!registro) return;

    const permissao = await solicitarPermissao();
    if (!permissao) return;

    try {
        const inscricao = await registro.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY),
        });

        // Envia a inscrição pro servidor
        await fetch('/barbearia/api/push/inscrever', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(inscricao),
        });

        console.log('Notificações ativadas!');
        atualizarBotao(true);
    } catch (err) {
        console.error('Erro ao inscrever:', err);
    }
}

async function cancelarNotificacoes() {
    const registro = await navigator.serviceWorker.ready;
    const inscricao = await registro.pushManager.getSubscription();

    if (inscricao) {
        await inscricao.unsubscribe();
        await fetch('/barbearia/api/push/cancelar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ endpoint: inscricao.endpoint }),
        });
    }

    atualizarBotao(false);
}

async function verificarInscricao() {
    if (!('serviceWorker' in navigator)) return;

    const registro = await navigator.serviceWorker.ready;
    const inscricao = await registro.pushManager.getSubscription();
    atualizarBotao(!!inscricao);
}

function atualizarBotao(inscrito) {
    const botao = document.getElementById('btn-notificacoes');
    if (!botao) return;

    if (inscrito) {
        botao.textContent = '🔔 Notificações ativas';
        botao.onclick = cancelarNotificacoes;
        botao.classList.add('text-amber-400');
        botao.classList.remove('text-gray-400');
    } else {
        botao.textContent = '🔕 Ativar notificações';
        botao.onclick = inscreverNotificacoes;
        botao.classList.remove('text-amber-400');
        botao.classList.add('text-gray-400');
    }
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64  = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
}

// Inicializa quando a página carrega
document.addEventListener('DOMContentLoaded', () => {
    registrarServiceWorker();
    verificarInscricao();
});