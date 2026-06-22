<?php

    // app/views/barbeiro/agenda.php
    $agendamentos = $agendamentos ?? [];
    $data = $data ?? date('Y-m-d');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Agenda</title>
</head>
<body>
    <h1>Minha Agenda</h1>

    <nav>
        <form action="/barbearia/barbeiro/agenda" method="GET">
            <label for="data">Ver agenda do dia:</label>
            <input type="date" id="data" name="data" value="<?= $data ?>">
            <button type="submit">Buscar</button>
        </form>
    </nav>

    <br>

    <?php if (empty($agendamentos)): ?>
        <p>Nenhum agendamento para este dia.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Horário</th>
                    <th>Cliente</th>
                    <th>Serviço</th>
                    <th>Duração</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($agendamentos as $ag): ?>
                    <tr>
                        <td>
                            <?= $ag['hora'] ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($ag['cliente']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($ag['servico']) ?>
                        </td>
                        <td>
                            <?= $ag['duracao_min'] ?>min
                        </td>
                        <td>
                            <?= htmlspecialchars($ag['status']) ?>
                        </td>
                        <td>
                            <form action="/barbearia/barbeiro/status" method="POST">
                                <input type="hidden" name="id" value="<?= $ag['id'] ?>">
                                <select name="status">
                                    <option value="confirmado" <?= $ag['status'] === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                                    <option value="concluido" <?= $ag['status'] === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                                    <option value="cancelado" <?= $ag['status'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                </select>
                                <button type="submit">Atualizar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <a href="/barbearia/login">Sair</a>
</body>
</html>