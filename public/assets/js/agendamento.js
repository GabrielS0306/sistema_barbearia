// public/assets/js/agendamento.js
const formAgendamento = document.getElementById('form-agendamento');

if (formAgendamento) {
    formAgendamento.addEventListener('submit', function (e) {
        let valido = true;

        const barbeiro = document.getElementById('barbeiro_id');
        const servico  = document.getElementById('servico_id');
        const data     = document.getElementById('data');
        const hora     = document.getElementById('hora');

        [barbeiro, servico, data, hora].forEach(limparErro);

        if (!barbeiro.value) {
            mostrarErro(barbeiro, 'Selecione um barbeiro.');
            valido = false;
        }

        if (!servico.value) {
            mostrarErro(servico, 'Selecione um serviço.');
            valido = false;
        }

        if (!data.value) {
            mostrarErro(data, 'Selecione uma data.');
            valido = false;
        } else if (data.value < new Date().toISOString().split('T')[0]) {
            mostrarErro(data, 'A data não pode ser no passado.');
            valido = false;
        }

        if (!hora.value) {
            mostrarErro(hora, 'Selecione um horário.');
            valido = false;
        }

        if (!valido) e.preventDefault();
    });
}