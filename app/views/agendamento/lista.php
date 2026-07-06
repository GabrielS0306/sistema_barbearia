<?php

    // app/views/agendamento/lista.php
    $agendamentos = $agendamentos ?? [];
    $titulo = 'Meus Agendamentos';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="flex items-center justify-between mb-8">
    <h1 class="text-3xl font-bold text-amber-400">Meus Agendamentos</h1>

    <a href="/barbearia/agendamento/novo"
        class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-4 py-2 rounded-lg transition text-sm">
        + Novo Agendamento
    </a>
</div>

<?php if (empty($agendamentos)): ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center text-gray-500">
        Você ainda não tem agendamentos.
    </div>
<?php else: ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Data</th>
                    <th class="px-6 py-3 text-left">Horário</th>
                    <th class="px-6 py-3 text-left">Barbeiro</th>
                    <th class="px-6 py-3 text-left">Serviço</th>
                    <th class="px-6 py-3 text-left">Preço</th>
                    <th class="px-6 py-3 text-left">Status</th>
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
                        <td class="px-6 py-4">
                            <?= date('d/m/Y', strtotime($ag['data'])) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= $ag['hora'] ?>
                        </td>
                        <td class="px-6 py-4 font-medium">
                            <?= htmlspecialchars($ag['barbeiro']) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-400">
                            <?= htmlspecialchars($ag['servico']) ?>
                        </td>
                        <td class="px-6 py-4 text-amber-400">
                            R$ <?= number_format($ag['preco'], 2, ',', '.') ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?= $statusClasses ?>">
                                <?= htmlspecialchars($ag['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>