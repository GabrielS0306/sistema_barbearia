<?php

    $servico = $servico ?? null;
    $editando = $servico !== null;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $editando ? 'Editar' : 'Novo' ?> Serviço - Admin</title>
</head>
<body>
    <h1><?= $editando ? 'Editar Serviço' : 'Novo Serviço' ?></h1>

    <form action="/barbearia/admin/servicos/<?= $editando ? 'editar' : 'novo' ?>" method="POST">
        <?php if ($editando): ?>
            <input type="hidden" name="id" value="<?= $servico['id'] ?? '' ?>">
        <?php endif; ?>

        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($servico['nome'] ?? '') ?>" required>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao">
            <?= htmlspecialchars($servico['descricao'] ?? '') ?>
        </textarea>

        <label for="preco">Preço (R$)</label>
        <input type="number" id="preco" name="preco" step="0.01" min="0" value="<?= $servico['preco'] ?? '' ?>" required>

        <label for="duracao_min">Duração (minutos)</label>
        <input type="number" id="duracao_min" name="duracao_min" min="1" value="<?= $servico['duracao_min'] ?? '' ?>" required>

        <button type="submit">
            <?= $editando ? 'Salvar Alterações' : 'Criar Serviço' ?>
        </button>
    </form>

    <a href="/barbearia/admin/servicos">Cancelar</a>
</body>
</html>