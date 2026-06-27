// public/assets/js/utils.js

function mostrarErro(input, mensagem) {
    input.classList.add('border-red-500');
    input.classList.remove('border-gray-700');

    const erroExistente = input.parentElement.querySelector('.erro-msg');
    if (!erroExistente) {
        const span = document.createElement('span');
        span.className = 'erro-msg text-red-400 text-xs mt-1';
        span.textContent = mensagem;
        input.parentElement.appendChild(span);
    }
}

function limparErro(input) {
    input.classList.remove('border-red-500');
    input.classList.add('border-gray-700');
    const erroMsg = input.parentElement.querySelector('.erro-msg');
    if (erroMsg) erroMsg.remove();
}

function validarEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}