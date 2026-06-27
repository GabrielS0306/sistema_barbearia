<?php

    // app/views/admin/servico-form.php
    $servico = $servico ?? null;
    $editando = $servico !== null;
    $titulo = $editando ? 'Editar Serviço' : 'Novo Serviço';
    $script = 'servico.js';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="max-w-lg mx-auto">
    <h1 class="text-3xl font-bold text-amber-400 mb-8">
        <?= $editando ? 'Editar Serviço' : 'Novo Serviço' ?>
    </h1>

    <form id="form-servico" action="/barbearia/admin/servicos/<?= $editando ? 'editar' : 'novo' ?>" method="POST" class="bg-gray-900 border border-gray-800 rounded-xl p-8 flex flex-col gap-5" novalidate>

        <?php if ($editando): ?>
            <input type="hidden" name="id" value="<?= $servico['id'] ?? '' ?>">
        <?php endif; ?>

        <div class="flex flex-col gap-1">
            <label for="nome" class="text-sm text-gray-400">Nome</label>

            <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($servico['nome'] ?? '') ?>"
                class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
        </div>

        <div class="flex flex-col gap-1">
            <label for="descricao" class="text-sm text-gray-400">Descrição</label>

            <textarea id="descricao" name="descricao" rows="3" class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
                <?= htmlspecialchars($servico['descricao'] ?? '') ?>
            </textarea>
        </div>

        <div class="flex gap-4">
            <div class="flex flex-col gap-1 flex-1">
                <label for="preco" class="text-sm text-gray-400">Preço (R$)</label>

                <input type="number" id="preco" name="preco" step="0.01" min="0" required
                    value="<?= $servico['preco'] ?? '' ?>"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
            </div>

            <div class="flex flex-col gap-1 flex-1">
                <label for="duracao_min" class="text-sm text-gray-400">Duração (min)</label>

                <input type="number" id="duracao_min" name="duracao_min" min="1" required
                    value="<?= $servico['duracao_min'] ?? '' ?>"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
            </div>
        </div>

        <div class="flex gap-3 mt-2">
            <button type="submit" class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-6 py-2 rounded-lg transition">
                <?= $editando ? 'Salvar Alterações' : 'Criar Serviço' ?>
            </button>

            <a href="/barbearia/admin/servicos" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-6 py-2 rounded-lg transition">
                Cancelar
            </a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>