<?php

    // app/views/admin/agendamento-historico.php
    $agendamento = $agendamento ?? [];
    $historico   = $historico ?? [];
    $titulo      = 'Histórico do Agendamento';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="mb-6">
    <a href="/barbearia/admin/agendamentos" class="text-amber-400 hover:underline text-sm">
        ← Voltar para agendamentos
    </a>
</div>

<h1 class="text-3xl font-bold text-amber-400 mb-6">Histórico do Agendamento</h1>

<!-- Resumo do agendamento -->
<div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-8">
    <h2 class="text-sm text-gray-400 uppercase mb-3">Detalhes</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div>
            <p class="text-gray-400">Cliente</p>
            <p class="font-medium"><?= htmlspecialchars($agendamento['cliente']) ?></p>
        </div>
        <div>
            <p class="text-gray-400">Barbeiro</p>
            <p class="font-medium"><?= htmlspecialchars($agendamento['barbeiro']) ?></p>
        </div>
        <div>
            <p class="text-gray-400">Data</p>
            <p class="font-medium"><?= date('d/m/Y', strtotime($agendamento['data'])) ?></p>
        </div>
        <div>
            <p class="text-gray-400">Horário</p>
            <p class="font-medium"><?= substr($agendamento['hora'], 0, 5) ?></p>
        </div>
        <div>
            <p class="text-gray-400">Serviço</p>
            <p class="font-medium"><?= htmlspecialchars($agendamento['servico']) ?></p>
        </div>
        <div>
            <p class="text-gray-400">Status</p>
            <p class="font-medium"><?= ucfirst($agendamento['status']) ?></p>
        </div>
        <div>
            <p class="text-gray-400">Pagamento</p>
            <p class="font-medium"><?= ucfirst($agendamento['status_pagamento']) ?></p>
        </div>
        <div>
            <p class="text-gray-400">Forma</p>
            <p class="font-medium"><?= ucfirst($agendamento['forma_pagamento']) ?></p>
        </div>
    </div>
</div>

<!-- Linha do tempo -->
<h2 class="text-xl font-bold mb-4">Linha do tempo</h2>

<?php if (empty($historico)): ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center text-gray-500">
        Nenhum registro no histórico.
    </div>
<?php else: ?>
    <div class="relative">
        <!-- Linha vertical -->
        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-800"></div>

        <div class="flex flex-col gap-6">
            <?php foreach ($historico as $item): ?>
                <?php
                    $corAcao = match($item['acao']) {
                        'criado'                => 'bg-blue-900 text-blue-300',
                        'confirmado'            => 'bg-green-900 text-green-300',
                        'concluido'             => 'bg-blue-900 text-blue-300',
                        'cancelado'             => 'bg-red-900 text-red-300',
                        'adiado'                => 'bg-yellow-900 text-yellow-300',
                        'reembolso_solicitado'  => 'bg-orange-900 text-orange-300',
                        'reembolso_confirmado'  => 'bg-green-900 text-green-300',
                        default                 => 'bg-gray-800 text-gray-300',
                    };
                ?>
                <div class="relative pl-12">
                    <!-- Bolinha na linha do tempo -->
                    <div class="absolute left-2 top-1 w-4 h-4 rounded-full bg-amber-400 border-2 border-gray-950"></div>

                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?= $corAcao ?>">
                                <?= ucfirst(str_replace('_', ' ', $item['acao'])) ?>
                            </span>

                            <span class="text-gray-500 text-xs">
                                <?= date('d/m/Y H:i', strtotime($item['created_at'])) ?>
                            </span>
                        </div>

                        <p class="text-gray-400 text-sm"><?= htmlspecialchars($item['detalhes']) ?></p>

                        <p class="text-gray-600 text-xs mt-1">
                            Por: <?= htmlspecialchars($item['email']) ?> (<?= $item['role_label'] ?>)
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>