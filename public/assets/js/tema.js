// public/assets/js/tema.js

const CHAVE = 'tema';
const html  = document.documentElement;

function aplicarTema(tema) {
    if (tema === 'claro') {
        html.classList.add('light');
    } else {
        html.classList.remove('light');
    }

    localStorage.setItem(CHAVE, tema);
}

function alternarTema() {
    const atual = localStorage.getItem(CHAVE) || 'escuro';

    aplicarTema(atual === 'escuro' ? 'claro' : 'escuro');

    atualizarIcone();
}

function atualizarIcone() {
    const tema  = localStorage.getItem(CHAVE) || 'escuro';
    const botao = document.getElementById('btn-tema');

    if (botao) {
        botao.innerHTML = tema === 'escuro' ? '☀️' : '🌙';

        botao.title     = tema === 'escuro' ? 'Mudar para tema claro' : 'Mudar para tema escuro';
    }
}

// Aplica o tema salvo no localStorage ao carregar a página
const temaSalvo = localStorage.getItem(CHAVE) || 'escuro';
aplicarTema(temaSalvo);
atualizarIcone();