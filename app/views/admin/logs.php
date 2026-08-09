<?php

    // app/views/admin/logs.php
    $linhas = $linhas ?? [];
    $titulo = 'Logs do Sistema';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-amber-400">Logs do Sistema</h1>

    <p class="text-gray-400 mt-1">
        Monitoramento de erros e avisos — <?= date('m/Y') ?>
    </p>
</div>

<?php if (empty($linhas)): ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center text-gray-500">
        Nenhum log registrado este mês.
    </div>
<?php else: ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="flex flex-col divide-y divide-gray-800 max-h-screen overflow-y-auto">
            <?php foreach ($linhas as $linha): ?>
                <?php
                    $corLinha = 'text-gray-300';

                    if (str_contains($linha, '[ERRO]'))  $corLinha = 'text-red-400';
                    if (str_contains($linha, '[AVISO]')) $corLinha = 'text-yellow-400';
                    if (str_contains($linha, '[INFO]'))  $corLinha = 'text-blue-400';
                ?>
                <div class="px-4 py-2 font-mono text-xs <?= $corLinha ?> hover:bg-gray-800 transition">
                    <?= htmlspecialchars($linha) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <p class="text-gray-500 text-xs mt-3">
        Total: <?= count($linhas) ?> registros
    </p>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>