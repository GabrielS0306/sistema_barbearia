// public/assets/js/agendamento.js
// SEM o DOMContentLoaded — o script já carrega com o DOM pronto

const formAgendamento = document.getElementById('form-agendamento');
const selectBarbeiro  = document.getElementById('barbeiro_id');
const inputData       = document.getElementById('data');
const selectHora      = document.getElementById('hora');

async function carregarHorarios() {
    const barbeiroId = selectBarbeiro.value;
    const data       = inputData.value;

    if (!barbeiroId || !data) return;

    selectHora.innerHTML = '<option value="">Carregando...</option>';
    selectHora.disabled  = true;

    try {
        const response = await fetch(`/barbearia/api/horarios?barbeiro_id=${barbeiroId}&data=${data}`);
        const horarios = await response.json();

        selectHora.innerHTML = '<option value="">Selecione um horário</option>';

        horarios.forEach(h => {
            const option       = document.createElement('option');
            option.value       = h.hora;
            option.textContent = h.disponivel ? h.hora : `${h.hora} — indisponível`;
            option.disabled    = !h.disponivel;
            selectHora.appendChild(option);
        });

        selectHora.disabled = false;
    } catch (err) {
        selectHora.innerHTML = '<option value="">Erro ao carregar horários</option>';
        selectHora.disabled  = false;
    }
}

if (selectBarbeiro) selectBarbeiro.addEventListener('change', carregarHorarios);
if (inputData) inputData.addEventListener('change', carregarHorarios);

// Validação do formulário
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