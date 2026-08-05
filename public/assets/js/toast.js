// public/assets/js/toast.js

function mostrarToast(mensagem, tipo = 'sucesso') {
    const container = document.getElementById('toast-container');

    if (!container) return;

    const cores = {
        sucesso: 'bg-green-900 border-green-700 text-green-200',
        erro:    'bg-red-900 border-red-700 text-red-200',
        aviso:   'bg-yellow-900 border-yellow-700 text-yellow-200',
        info:    'bg-blue-900 border-blue-700 text-blue-200',
    };

    const icones = {
        sucesso: '✓',
        erro:    '✕',
        aviso:   '⚠',
        info:    'ℹ',
    };

    const toast = document.createElement('div');

    toast.className = `flex items-center gap-3 border px-4 py-3 rounded-lg shadow-lg text-sm transition-all duration-300 opacity-0 translate-y-2 ${cores[tipo] || cores.info}`;
    toast.innerHTML = `
        <span class="font-bold text-lg">${icones[tipo] || icones.info}</span>
        <span class="flex-1">${mensagem}</span>
        <button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 transition text-lg leading-none">×</button>
    `;

    container.appendChild(toast);

    // Anima entrada
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toast.classList.remove('opacity-0', 'translate-y-2');
        });
    });

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// Inicializa toasts vindos do PHP via sessão 
// setTimeout(function () {
//     const toasts = window.__toasts || [];

//     toasts.array.forEach(t => mostrarToast(t.mensagem, t.tipo));
// }, 100);