<?php

    // app/views/admin/agendamentos.php
    $agendamentos = $agendamentos ?? [];
    $contagem     = $contagem ?? [];
    $barbeiros    = $barbeiros ?? [];
    $filtros      = $filtros ?? [];
    $titulo       = 'Agendamentos';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-amber-400 mb-6">Agendamentos</h1>

    <!-- Cards de resumo -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-gray-900 border border-yellow-800 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-yellow-400">
                <?= $contagem['pendente'] ?? 0 ?>
            </p>

            <p class="text-gray-400 text-sm mt-1">Pendentes</p>
        </div>

        <div class="bg-gray-900 border border-green-800 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-green-400">
                <?= $contagem['confirmado'] ?? 0 ?>
            </p>

            <p class="text-gray-400 text-sm mt-1">Confirmados</p>
        </div>

        <div class="bg-gray-900 border border-blue-800 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-blue-400">
                <?= $contagem['concluido'] ?? 0 ?>
            </p>

            <p class="text-gray-400 text-sm mt-1">Concluídos</p>
        </div>

        <div class="bg-gray-900 border border-red-800 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-red-400">
                <?= $contagem['cancelado'] ?? 0 ?>
            </p>

            <p class="text-gray-400 text-sm mt-1">Cancelados</p>
        </div>
    </div>

    <!-- Botão de relatório -->
    <div class="flex justify-end mb-4">
        <form action="/barbearia/admin/relatorio" method="GET" class="flex gap-3 items-end flex-wrap">
            <div class="flex flex-col gap-1">
                <label class="text-xs text-gray-400">De</label>
                <input type="date" name="data_inicio"
                    value="<?= date('Y-m-01') ?>"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-100 text-sm focus:outline-none focus:border-amber-400">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs text-gray-400">Até</label>
                <input type="date" name="data_fim"
                    value="<?= date('Y-m-d') ?>"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-100 text-sm focus:outline-none focus:border-amber-400">
            </div>
            <button type="submit"
                class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-4 py-2 rounded-lg transition text-sm">
                📄 Gerar Relatório PDF
            </button>
        </form>
    </div>

    <!-- Filtros -->
    <form action="/barbearia/admin/agendamentos" method="GET" class="bg-gray-900 border border-gray-800 rounded-xl p-4 flex flex-wrap gap-3 mb-6">
        <div class="flex flex-col gap-1 flex-1 min-w-[150px]">
            <label class="text-xs text-gray-400">Data</label>

            <input type="date" name="data" value="<?= htmlspecialchars($filtros['data'] ?? '') ?>"
                class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-100 text-sm focus:outline-none focus:border-amber-400">
        </div>

        <div class="flex flex-col gap-1 flex-1 min-w-[150px]">
            <label class="text-xs text-gray-400">Barbeiro</label>

            <select name="barbeiro_id"
                class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-100 text-sm focus:outline-none focus:border-amber-400">
                <option value="">Todos</option>
                <?php foreach ($barbeiros as $barbeiro): ?>
                    <option value="<?= $barbeiro['id'] ?>" <?= ($filtros['barbeiro_id'] ?? '') == $barbeiro['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($barbeiro['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex flex-col gap-1 flex-1 min-w-[150px]">
            <label class="text-xs text-gray-400">Status</label>

            <select name="status"
                class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-100 text-sm focus:outline-none focus:border-amber-400">
                <option value="">Todos</option>
                <option value="pendente" <?= ($filtros['status'] ?? '') === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="confirmado" <?= ($filtros['status'] ?? '') === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                <option value="concluido" <?= ($filtros['status'] ?? '') === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                <option value="cancelado" <?= ($filtros['status'] ?? '') === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit"
                class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-4 py-2 rounded-lg transition text-sm">
                Filtrar
            </button>

            <a href="/barbearia/admin/agendamentos"
                class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-4 py-2 rounded-lg transition text-sm">
                Limpar
            </a>
        </div>
    </form>

    <!-- Tabela -->
    <?php if (empty($agendamentos)): ?>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center text-gray-500">
            Nenhum agendamento encontrado.
        </div>
    <?php else: ?>
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Data</th>
                        <th class="px-6 py-3 text-left">Horário</th>
                        <th class="px-6 py-3 text-left">Cliente</th>
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
                                <?= htmlspecialchars($ag['cliente']) ?>
                            </td>

                            <td class="px-6 py-4 text-gray-400">
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
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>