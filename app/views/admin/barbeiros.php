<?php

    // app/views/admin/barbeiros.php
    $barbeiros = $barbeiros ?? [];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbeiros - Admin</title>
</head>
<body>
    <h1>Gerenciar Barbeiros</h1>

    <a href="/barbearia/admin/dashboard">Voltar ao Dashboard</a>

    <br>
    <br>

    <a href="/barbearia/admin/barbeiros/novo-usuario">+ Novo Barbeiro</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Especialidade</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($barbeiros as $barbeiro): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($barbeiro['nome']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($barbeiro['email']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($barbeiro['especialidade'] ?? '') ?>
                    </td>
                    <td>
                        <a href="/barbearia/admin/barbeiros/editar?id=<?= $barbeiro['id'] ?>">Editar</a>

                        <form 
                            action="/barbearia/admin/barbeiros/deletar" 
                            method="POST" 
                            style="display:inline;" 
                            onsubmit="return confirm('Tem certeza que deseja excluir este barbeiro?');"
                        >
                            <input type="hidden" name="id" value="<?= $barbeiro['id'] ?>">

                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>