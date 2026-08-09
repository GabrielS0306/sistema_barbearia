<?php

    // app/views/admin/barbeiro-servicos.php
    $barbeiro      = $barbeiro ?? null;
    $todosServicos = $todosServicos ?? [];
    $idsBarbeiro   = $idsBarbeiro ?? [];
    $titulo        = 'Serviços do Barbeiro';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="mb-6">
    <a href="/barbearia/admin/barbeiros" class="text-amber-400 hover:underline text-sm">
        ← Voltar para barbeiros
    </a>
</div>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-amber-400">Serviços do Barbeiro</h1>

    <p class="text-gray-400 mt-1">
        Configure quais serviços <strong class="text-gray-200"><?= htmlspecialchars($barbeiro['nome'] ?? '') ?></strong> oferece.
    </p>
</div>

<form action="/barbearia/admin/barbeiros/servicos?id=<?= $barbeiro['id'] ?? 0 ?>" method="POST" class="bg-gray-900 border border-gray-800 rounded-xl p-6">
    <?= Csrf::campo() ?>

    <?php if (empty($todosServicos)): ?>
        <p class="text-gray-500">Nenhum serviço cadastrado.</p>
    <?php else: ?>
        <div class="flex flex-col gap-3 mb-6">
            <?php foreach ($todosServicos as $servico): ?>
                <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-800 p-3 rounded-lg transition">
                    <input type="checkbox" name="servicos[]" value="<?= $servico['id'] ?>"
                        <?= in_array($servico['id'], $idsBarbeiro) ? 'checked' : '' ?>
                        class="accent-amber-400 w-4 h-4">

                    <div>
                        <p class="font-medium text-sm"><?= htmlspecialchars($servico['nome']) ?></p>

                        <p class="text-gray-500 text-xs">
                            R$ <?= number_format($servico['preco'], 2, ',', '.') ?> — <?= $servico['duracao_min'] ?>min
                        </p>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-6 py-2 rounded-lg transition">
            Salvar
        </button>
    <?php endif; ?>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>