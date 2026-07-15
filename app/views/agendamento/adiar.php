<?php

    // app/views/agendamento/adiar.php
    $ag     = $_SESSION['agendamento_adiar'] ?? [];
    $titulo = 'Adiar Agendamento';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="max-w-lg mx-auto">
    <h1 class="text-3xl font-bold text-amber-400 mb-2">Adiar Agendamento</h1>

    <p class="text-gray-400 mb-8">Escolha uma nova data e horário para o seu agendamento.</p>

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($_SESSION['erro']) ?>

            <?php unset($_SESSION['erro']); ?>
        </div>
    <?php endif; ?>

    <!-- Resumo do agendamento atual -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-6">
        <h2 class="text-sm text-gray-400 uppercase mb-3">Agendamento atual</h2>

        <div class="flex flex-col gap-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Data atual</span>

                <span class="font-medium"><?= date('d/m/Y', strtotime($ag['data'])) ?></span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-400">Horário atual</span>

                <span class="font-medium"><?= substr($ag['hora'], 0, 5) ?></span>
            </div>
        </div>
    </div>

    <!-- Formulário de adiamento -->
    <form action="/barbearia/agendamento/adiar" method="POST" class="bg-gray-900 border border-gray-800 rounded-xl p-8 flex flex-col gap-5">

        <input type="hidden" name="id" value="<?= $ag['id'] ?>">
        <input type="hidden" name="nova_data" id="nova_data_hidden">
        <input type="hidden" name="nova_hora" id="nova_hora_hidden">

        <div class="flex gap-4">
            <div class="flex flex-col gap-1 flex-1">
                <label for="nova_data" class="text-sm text-gray-400">Nova data</label>

                <input type="date" id="nova_data" name="nova_data" min="<?= date('Y-m-d') ?>" required class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
            </div>

            <div class="flex flex-col gap-1 flex-1">
                <label for="nova_hora" class="text-sm text-gray-400">Novo horário</label>

                <select id="nova_hora" name="nova_hora" required class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
                    <option value="">Selecione</option>
                        <?php
                            $horarios = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
                                        '11:00', '11:30', '13:00', '13:30', '14:00', '14:30',
                                        '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'];
                            foreach ($horarios as $h):
                        ?>
                            <option value="<?= $h ?>">
                                <?= $h ?>
                            </option>
                        <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="flex gap-3 mt-2">
            <button type="submit" class="flex-1 bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold py-3 rounded-lg transition">
                Confirmar Adiamento
            </button>

            <a href="/barbearia/agendamento/meus" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-6 py-3 rounded-lg transition">
                Cancelar
            </a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>