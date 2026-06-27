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