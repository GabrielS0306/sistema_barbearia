// public/assets/js/inatividade.js

const TEMPO_LIMITE = 15 * 60 * 1000; // 15 minutos
const AVISO_ANTES  = 1 * 60 * 1000;  // avisa 1 minuto antesj

let timerLogout;
let timerAviso;
let avisoVisivel = false;

// Cria o elemento de aviso na tela
const aviso = document.createElement('div');
aviso.id = 'aviso-inatividade';
aviso.style.cssText = `
    display: none;
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #1f2937;
    border: 1px solid #f59e0b;
    border-radius: 12px;
    padding: 16px 20px;
    color: #f3f4f6;
    font-family: Arial, sans-serif;
    font-size: 14px;
    z-index: 9999;
    max-width: 300px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.5);
`;

aviso.innerHTML = `
    <p style="color: #f59e0b; font-weight: bold; margin: 0 0 6px;">⚠️ Sessão expirando</p>
    <p style="margin: 0 0 12px; color: #9ca3af;">Você será desconectado em <strong id="contador-inatividade" style="color: #f3f4f6;">60</strong> segundos por inatividade.</p>
    <button onclick="resetarTimer()" style="background: #f59e0b; color: #111; border: none; padding: 6px 16px; border-radius: 8px; cursor: pointer; font-weight: bold;">
        Continuar conectado
    </button>
`;

document.body.appendChild(aviso);

function mostrarAviso() {
    aviso.style.display = 'block';
    avisoVisivel = true;

    let segundos = 60;
    const contador = document.getElementById('contador-inatividade');

    const intervalo = setInterval(() => {
        segundos--;
        if (contador) contador.textContent = segundos;
        if (contador <= 0) clearInterval(intervalo);
    }, 1000);
}

function mostrarAviso() {
    aviso.style.display = 'none';
    avisoVisivel = false;
}

function fazerLogout() {
    window.location.href = '/barbearia/logout';
}

function resetarTime() {
    clearTimeout(timerLogout);
    clearTimeout(timerAviso);
    esconderAviso();
    iniciarTimer();
}

function iniciarTimer() {
    timerAviso = setTimeout(() => {
        mostrarAviso();
    }, TEMPO_LIMITE - AVISO_ANTES);

    timerLogout = setTimeout(() => {
        fazerLogout();
    }, TEMPO_LIMITE);
}

// reseta o timer em qualquer interação do usuário
['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(evento => {
    document.addEventListener(evento, resetarTime);
})

// Inicia o timer após a página ser carregada
iniciarTimer();