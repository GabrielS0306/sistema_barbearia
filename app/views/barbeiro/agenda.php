<?php

    // app/views/barbeiro/agenda.php
    $agendamentos = $agendamentos ?? [];
    $data = $data ?? date('Y-m-d');
    $titulo = 'Minha Agenda';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-amber-400 mb-4">Minha Agenda</h1>

    <form action="/barbearia/barbeiro/agenda" method="GET" class="flex items-end gap-3">
        <div class="flex flex-col gap-1">
            <label for="data" class="text-sm text-gray-400">Selecionar data</label>

            <input type="date" id="data" name="data" value="<?= $data ?>"
                class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
        </div>

        <button type="submit"
            class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-4 py-2 rounded-lg transition">
            Buscar
        </button>
    </form>

    <p class="text-gray-400 mt-3 text-sm">
        Exibindo agenda de <span class="text-amber-400 font-medium"><?= date('d/m/Y', strtotime($data)) ?></span>
    </p>
</div>

<?php if (empty($agendamentos)): ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center text-gray-500">
        Nenhum agendamento para este dia.
    </div>
<?php else: ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Horário</th>
                    <th class="px-6 py-3 text-left">Cliente</th>
                    <th class="px-6 py-3 text-left">Serviço</th>
                    <th class="px-6 py-3 text-left">Duração</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Ação</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-800">
                <?php foreach ($agendamentos as $ag): ?>
                    <?php
                        $statusClasses = match($ag['status']) {
                            'confirmado' => 'bg-green-900 text-green-300',
                            'concluido'  => 'bg-blue-900 text-blue-300',
                            'cancelado'  => 'bg-red-900 text-red-300',
                            default      => 'bg-yellow-900 text-yellow-300',
                        };
                    ?>
                    <tr class="hover:bg-gray-800 transition">
                        <td class="px-6 py-4 font-medium">
                            <?= $ag['hora'] ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= htmlspecialchars($ag['cliente']) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-400">
                            <?= htmlspecialchars($ag['servico']) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-400">
                            <?= $ag['duracao_min'] ?>min
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?= $statusClasses ?>">
                                <?= htmlspecialchars($ag['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <form action="/barbearia/barbeiro/status" method="POST" class="flex gap-2 items-center">
                                <input type="hidden" name="id" value="<?= $ag['id'] ?>">
                                <select name="status"
                                    class="bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-gray-100 text-xs focus:outline-none focus:border-amber-400">
                                    <option value="confirmado" <?= $ag['status'] === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                                    <option value="concluido" <?= $ag['status'] === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                                    <option value="cancelado" <?= $ag['status'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                </select>
                                <button type="submit"
                                    class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-3 py-1 rounded-lg transition text-xs">
                                    Salvar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>