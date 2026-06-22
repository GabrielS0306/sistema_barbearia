<?php

    // app/views/agendamento/novo.php
    $barbeiros = $barbeiros ?? [];
    $servicos  = $servicos ?? [];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Agendamento</title>
</head>
<body>
    <h1>Agendar Horário</h1>
    <a href="/barbearia/agendamento/meus">Meus Agendamentos</a>

    <?php if (isset($_SESSION['erro'])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($_SESSION['erro']) ?>
        </p>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <p style="color: green;">
            <?= htmlspecialchars($_SESSION['sucesso']) ?>
        </p>
        <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>

    <form action="/barbearia/agendamento/novo" method="POST">
        <label for="barbeiro_id">Barbeiro:</label>
        <select id="barbeiro_id" name="barbeiro_id" required>
            <option value="">
                Selecione um barbeiro
            </option>

            <?php foreach ($barbeiros as $barbeiro): ?>
                <option value="<?= $barbeiro['id'] ?>">
                    <?= htmlspecialchars($barbeiro['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="servico_id">Serviço:</label>
        <select id="servico_id" name="servico_id" required>
            <option value="">
                Selecione um serviço
            </option>
            <?php foreach ($servicos as $servico): ?>
                <option value="<?= $servico['id'] ?>">
                    <?= htmlspecialchars($servico['nome']) ?> —
                    R$ <?= number_format($servico['preco'], 2, ',', '.') ?>
                    (<?= $servico['duracao_min'] ?>min)
                </option>
            <?php endforeach; ?>
        </select>

        <label for="data">Data:</label>
        <input type="date" id="data" name="data" min="<?= date('Y-m-d') ?>" required>

        <label for="hora">Horário:</label>
        <select id="hora" name="hora" required>
            <option value="">Selecione um horário</option>
            <?php

                $horarios = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
                            '11:00', '11:30', '13:00', '13:30', '14:00', '14:30',
                            '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'];
                foreach ($horarios as $h):

            ?>
                <option value="<?= $h ?>">
                    <?= $h ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Confirmar Agendamento</button>
    </form>
</body>
</html>