// public/js/validacoes.js

// Utilitários
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

// Validação do formulário de login
const formLogin = document.getElementById('form-login');

if (formLogin) {
    formLogin.addEventListener('submit', function (e) {
        let valido = true;

        const email = document.getElementById('email');
        const senha = document.getElementById('senha');

        limparErro(email);
        limparErro(senha);

        if (!email.value.trim()) {
            mostrarErro(email, 'Informe o email.');
            valido = false;
        } else if (!validarEmail(email.value.trim())) {
            mostrarErro(email, 'Email inválido.');
            valido = false;
        }

        if (!senha.value) {
            mostrarErro(senha, 'Informe a senha.');
            valido = false;
        }

        if (!valido) e.preventDefault();
    });
}

// Validação do formulário de registro
const formRegister = document.getElementById('form-register');
if (formRegister) {
    formRegister.addEventListener('submit', function (e) {
        let valido = true;

        const nome           = document.getElementById('nome');
        const email          = document.getElementById('email');
        const senha          = document.getElementById('senha');
        const confirmarSenha = document.getElementById('confirmar_senha');

        [nome, email, senha, confirmarSenha].forEach(limparErro);

        if (!nome.value.trim()) {
            mostrarErro(nome, 'Informe o nome.');
            valido = false;
        }

        if (!email.value.trim()) {
            mostrarErro(email, 'Informe o email.');
            valido = false;
        } else if (!validarEmail(email.value.trim())) {
            mostrarErro(email, 'Email inválido.');
            valido = false;
        }

        if (!senha.value) {
            mostrarErro(senha, 'Informe a senha.');
            valido = false;
        } else if (senha.value.length < 6) {
            mostrarErro(senha, 'Mínimo de 6 caracteres.');
            valido = false;
        }

        if (!confirmarSenha.value) {
            mostrarErro(confirmarSenha, 'Confirme a senha.');
            valido = false;
        } else if (senha.value !== confirmarSenha.value) {
            mostrarErro(confirmarSenha, 'As senhas não coincidem.');
            valido = false;
        }

        if (!valido) e.preventDefault();
    });
}