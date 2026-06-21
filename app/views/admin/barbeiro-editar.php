<?php
// app/views/admin/barbeiro-editar.php
$barbeiro = $barbeiro ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Barbeiro</title>
</head>
<body>
    <h1>Editar Barbeiro</h1>

    <?php if ($barbeiro): ?>
        <form action="/barbearia/admin/barbeiros/editar" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $barbeiro['id'] ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($barbeiro['nome']) ?>" required>

            <label for="especialidade">Especialidade:</label>
            <input type="text" id="especialidade" name="especialidade" value="<?= htmlspecialchars($barbeiro['especialidade'] ?? '') ?>">

            <?php if (!empty($barbeiro['foto'])): ?>
                <p>Foto atual:</p>
                <img src="/barbearia/public/uploads/barbeiros/<?= htmlspecialchars($barbeiro['foto']) ?>" alt="Foto atual" width="120">
            <?php endif; ?>

            <label for="foto">Trocar foto (opcional):</label>
            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">

            <button type="submit">Salvar Alterações</button>
        </form>
    <?php else: ?>
        <p>Barbeiro não encontrado.</p>
    <?php endif; ?>

    <a href="/barbearia/admin/barbeiros">Voltar</a>
</body>
</html>