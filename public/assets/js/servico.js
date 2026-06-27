// public/assets/js/servico.js

const formServico = document.getElementById('form-servico');

if (formServico) {
    formServico.addEventListener('submit', function (e) {
        let valido = true;

        const nome       = document.getElementById('nome');
        const preco      = document.getElementById('preco');
        const duracao    = document.getElementById('duracao_min');

        [nome, preco, duracao].forEach(limparErro);

        if (!nome.value.trim()) {
            mostrarErro(nome, 'Informe o nome do serviço.');
            valido = false;
        }

        if (!preco.value || parseFloat(preco.value) <= 0) {
            mostrarErro(preco, 'Informe um preço válido.');
            valido = false;
        }

        if (!duracao.value || parseInt(duracao.value) <= 0) {
            mostrarErro(duracao, 'Informe uma duração válida.');
            valido = false;
        }

        if (!valido) e.preventDefault();
    });
}