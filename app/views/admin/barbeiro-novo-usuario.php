<?php

    // app/views/admin/barbeiro-novo-usuario.php

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Barbeiro - Conta</title>
</head>
<body>
    <h1>Passo 1: Criar conta do barbeiro</h1>

    <?php if (isset($_SESSION['erro'])): ?>
        <p style="color: red;">
            <?= htmlspecialchars($_SESSION['erro']) ?>
        </p>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

    <form action="/barbearia/admin/barbeiros/novo-usuario" method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Criar conta e continuar</button>
    </form>

    <a href="/barbearia/admin/barbeiros">Cancelar</a>
</body>
</html>