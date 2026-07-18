<?php

    // app/views/agendamento/novo.php
    $barbeiros = $barbeiros ?? [];
    $servicos  = $servicos ?? [];
    $titulo = 'Novo Agendamento';
    $script = 'agendamento.js';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="max-w-lg mx-auto">
    <h1 class="text-3xl font-bold text-amber-400 mb-8">Agendar Horário</h1>

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($_SESSION['erro']) ?>
            <?php unset($_SESSION['erro']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($_SESSION['sucesso']) ?>
            <?php unset($_SESSION['sucesso']); ?>
        </div>
    <?php endif; ?>

    <form id="form-agendamento" action="/barbearia/agendamento/novo" method="POST"
        class="bg-gray-900 border border-gray-800 rounded-xl p-8 flex flex-col gap-5" novalidate>
        <?= Csrf::campo() ?>

        <div class="flex flex-col gap-1">
            <label for="barbeiro_id" class="text-sm text-gray-400">Barbeiro</label>

            <select id="barbeiro_id" name="barbeiro_id" required
                class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
                <option value="">Selecione um barbeiro</option>
                <?php foreach ($barbeiros as $barbeiro): ?>
                    <option value="<?= $barbeiro['id'] ?>">
                        <?= htmlspecialchars($barbeiro['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex flex-col gap-1">
            <label for="servico_id" class="text-sm text-gray-400">Serviço</label>

            <select id="servico_id" name="servico_id" required
                class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
                <option value="">Selecione um serviço</option>
                <?php foreach ($servicos as $servico): ?>
                    <option value="<?= $servico['id'] ?>">
                        <?= htmlspecialchars($servico['nome']) ?> —
                        R$ <?= number_format($servico['preco'], 2, ',', '.') ?>
                        (<?= $servico['duracao_min'] ?>min)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex gap-4">
            <div class="flex flex-col gap-1 flex-1">
                <label for="data" class="text-sm text-gray-400">Data</label>

                <input type="date" id="data" name="data" min="<?= date('Y-m-d') ?>" required
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
            </div>

            <div class="flex flex-col gap-1 flex-1">
                <label for="hora" class="text-sm text-gray-400">Horário</label>

                <select id="hora" name="hora" required
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
                    <option value="">Selecione</option>

                    <?php
                        $horarios = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
                                    '11:00', '11:30', '13:00', '13:30', '14:00', '14:30',
                                    '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'];
                        foreach ($horarios as $h):
                    ?>
                        <option value="<?= $h ?>"><?= $h ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <button type="submit"
            class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold py-2 rounded-lg transition mt-2">
            Confirmar Agendamento
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="/barbearia/agendamento/meus" class="text-amber-400 hover:underline text-sm">
            Ver meus agendamentos →
        </a>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>