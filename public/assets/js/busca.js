// public/assets/js/busca.js

function iniciarBusca(inputId, tabelaId, colunas) {
    const input  = document.getElementById(inputId);
    const tabela = document.getElementById(tabelaId);

    if (!input || !tabela) return;

    input.addEventListener('input', function () {
        const termo  = this.value.toLowerCase().trim();
        const linhas = tabela.querySelectorAll('tr');

        linhas.forEach(linha => {
            const textos = colunas.map(col => {
                const td = linha.querySelectorAll('td')[col];
                
                return td ? td.textContent.toLocaleLowerCase() : '';
            });

            const encontrou = textos.some(texto => texto.includes(termo));

            linha.style.display = encontrou ? '' : 'none';
        });
    });
}

// Busca na listagem de serviços (colunas: 0=nome, 1=descrição)
iniciarBusca('busca-servicos', 'tabela-servicos', [0, 1]);

// Busca na listagem de barbeiros (colunas: 1=nome, 2=email, 3=especialidade)
iniciarBusca('busca-barbeiros', 'tabela-barbeiros', [1, 2, 3]);