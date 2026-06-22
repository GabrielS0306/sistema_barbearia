<?php

    // app/views/agendamento/lista.php
    $agendamentos = $agendamentos ?? [];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos</title>
</head>
<body>
    <h1>Meus Agendamentos</h1>
    <a href="/barbearia/agendamento/novo">+ Novo Agendamento</a>

    <?php if (empty($agendamentos)): ?>
        <p>Você ainda não tem agendamentos.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Barbeiro</th>
                    <th>Serviço</th>
                    <th>Preço</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($agendamentos as $ag): ?>
                    <tr>
                        <td>
                            <?= date('d/m/Y', strtotime($ag['data'])) ?>
                        </td>
                        <td>
                            <?= $ag['hora'] ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($ag['barbeiro']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($ag['servico']) ?>
                        </td>
                        <td>
                            R$ <?= number_format($ag['preco'], 2, ',', '.') ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($ag['status']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>