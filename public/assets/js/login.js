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