<?php

    // app/views/admin/servicos.php
    $servicos = $servicos ?? [];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Barbeiros - Admin</title>
</head>
<body>
    <h1>Gerenciar Serviços</h1>
    <a href="/barbearia/admin/dashboard">Voltar</a>
    <br><br>
    <a href="/barbearia/admin/servicos/novo">+ Novo Serviço</a>

    <table border="1" cellpadding="8" cellspacin="0">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Decrição</th>
                <th>Preço</th>
                <th>Duração (min)</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($servicos as $servico): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($servico['nome']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($servico['descricao']) ?>
                    </td>
                    <td>
                        R$ <?= number_format($servico['preco'], 2, ',', '.') ?>
                    </td>
                    <td>
                        <a href="/barbearia/admin/servicos/editar?id=<?= $servico['id'] ?>">Editar</a>

                        <form action="/barbearia/admin/servicos/deletar" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este serviço?');">
                            <input type="hidden" name="id" value="<?= $servico['id'] ?>">
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>