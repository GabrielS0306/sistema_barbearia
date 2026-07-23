<?php

    // app/views/admin/dashboard.php
    $totalClientes    = $totalClientes ?? 0;
    $totalBarbeiros   = $totalBarbeiros ?? 0;
    $agendamentosHoje = $agendamentosHoje ?? 0;
    $receitaMes       = $receitaMes ?? 0;
    $porStatus        = $porStatus ?? [];
    $proximos         = $proximos ?? [];
    $titulo           = 'Dashboard';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-amber-400">Painel Administrativo</h1>
    <p class="text-gray-400 mt-1">Visão geral do sistema</p>
</div>

<!-- Cards de métricas -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <p class="text-gray-400 text-sm mb-1">Clientes</p>
        <p class="text-3xl font-bold text-amber-400"><?= $totalClientes ?></p>
        <p class="text-gray-500 text-xs mt-1">cadastrados</p>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <p class="text-gray-400 text-sm mb-1">Barbeiros</p>
        <p class="text-3xl font-bold text-amber-400"><?= $totalBarbeiros ?></p>
        <p class="text-gray-500 text-xs mt-1">ativos</p>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <p class="text-gray-400 text-sm mb-1">Hoje</p>
        <p class="text-3xl font-bold text-amber-400"><?= $agendamentosHoje ?></p>
        <p class="text-gray-500 text-xs mt-1">agendamentos</p>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <p class="text-gray-400 text-sm mb-1">Receita do mês</p>
        <p class="text-2xl font-bold text-green-400">R$ <?= number_format($receitaMes, 2, ',', '.') ?></p>
        <p class="text-gray-500 text-xs mt-1">concluídos</p>
    </div>
</div>

<!-- Status do mês -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <h2 class="text-lg font-bold mb-4">Agendamentos do mês</h2>

        <div class="flex flex-col gap-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-400 text-sm">Pendentes</span>

                <span class="bg-yellow-900 text-yellow-300 px-3 py-1 rounded-full text-xs font-medium">
                    <?= $porStatus['pendente'] ?>
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-gray-400 text-sm">Confirmados</span>

                <span class="bg-green-900 text-green-300 px-3 py-1 rounded-full text-xs font-medium">
                    <?= $porStatus['confirmado'] ?>
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-gray-400 text-sm">Concluídos</span>

                <span class="bg-blue-900 text-blue-300 px-3 py-1 rounded-full text-xs font-medium">
                    <?= $porStatus['concluido'] ?>
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-gray-400 text-sm">Cancelados</span>

                <span class="bg-red-900 text-red-300 px-3 py-1 rounded-full text-xs font-medium">
                    <?= $porStatus['cancelado'] ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Próximos agendamentos -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
        <h2 class="text-lg font-bold mb-4">Próximos agendamentos</h2>

        <?php if (empty($proximos)): ?>
            <p class="text-gray-500 text-sm">Nenhum agendamento próximo.</p>
        <?php else: ?>
            <div class="flex flex-col gap-3">
                <?php foreach ($proximos as $ag): ?>
                    <div class="flex justify-between items-start border-b border-gray-800 pb-3">
                        <div>
                            <p class="font-medium text-sm">
                                <?= htmlspecialchars($ag['cliente']) ?>
                            </p>

                            <p class="text-gray-500 text-xs">
                                <?= htmlspecialchars($ag['servico']) ?> com <?= htmlspecialchars($ag['barbeiro']) ?>
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-amber-400 text-sm font-medium">
                                <?= date('d/m', strtotime($ag['data'])) ?>
                            </p>

                            <p class="text-gray-500 text-xs">
                                <?= substr($ag['hora'], 0, 5) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Links rápidos -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="/barbearia/admin/servicos" class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-amber-400 transition group">
        <div class="text-amber-400 text-3xl mb-3">✂</div>

        <h2 class="text-xl font-bold group-hover:text-amber-400 transition">Serviços</h2>

        <p class="text-gray-500 text-sm mt-1">Gerencie os serviços oferecidos</p>
    </a>

    <a href="/barbearia/admin/barbeiros" class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-amber-400 transition group">
        <div class="text-amber-400 text-3xl mb-3">👤</div>

        <h2 class="text-xl font-bold group-hover:text-amber-400 transition">Barbeiros</h2>

        <p class="text-gray-500 text-sm mt-1">Gerencie a equipe de barbeiros</p>
    </a>

    <a href="/barbearia/admin/agendamentos" class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-amber-400 transition group">
        <div class="text-amber-400 text-3xl mb-3">📅</div>

        <h2 class="text-xl font-bold group-hover:text-amber-400 transition">Agendamentos</h2>
        
        <p class="text-gray-500 text-sm mt-1">Visualize todos os agendamentos</p>
    </a>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>