// public/assets/js/barbeiro.js

const formBarbeiroUsuario = document.getElementById('form-barbeiro-usuario');

if (formBarbeiroUsuario) {
    formBarbeiroUsuario.addEventListener('submit', function (e) {
        let valido = true;

        const email = document.getElementById('email');
        const senha = document.getElementById('senha');

        [email, senha].forEach(limparErro);

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

        if (!valido) e.preventDefault();
    });
}

const formBarbeiroPerfil = document.getElementById('form-barbeiro-perfil');

if (formBarbeiroPerfil) {
    formBarbeiroPerfil.addEventListener('submit', function (e) {
        let valido = true;

        const nome = document.getElementById('nome');
        const usuarioId = document.getElementById('usuario_id');

        [nome, usuarioId].forEach(limparErro);

        if (!usuarioId.value) {
            mostrarErro(usuarioId, 'Selecione uma conta vinculada.');
            valido = false;
        }

        if (!nome.value.trim()) {
            mostrarErro(nome, 'Informe o nome de exibição.');
            valido = false;
        }

        if (!valido) e.preventDefault();
    });
}

const formBarbeiroEditar = document.getElementById('form-barbeiro-editar');

if (formBarbeiroEditar) {
    formBarbeiroEditar.addEventListener('submit', function (e) {
        let valido = true;

        const nome = document.getElementById('nome');

        limparErro(nome);

        if (!nome.value.trim()) {
            mostrarErro(nome, 'Informe o nome.');
            valido = false;
        }

        if (!valido) e.preventDefault();
    });
}