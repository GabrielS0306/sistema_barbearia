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

function toggleSenha(inputId, iconeId) {
    const input = document.getElementById(inputId);
    const icone = document.getElementById(iconeId);

    const eyeIcon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;

    const eyeSlashIcon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>`;

    if (input.type === 'password') {
        input.type = 'text';
        icone.innerHTML = eyeSlashIcon;
    } else {
        input.type = 'password';
        icone.innerHTML = eyeIcon;
    }
}