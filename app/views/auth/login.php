<?php
// app/views/auth/login.php
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Barbearia</title>
    <!-- css -->
    <link rel="stylesheet" href="/barbearia/public/assets/css/login_register.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <?php if (isset($_SESSION['erro'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_SESSION['erro']) ?></p>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>

        <form action="/barbearia/login" method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <button type="submit" class="btn">Entrar</button>
        </form>
    </div>
</body>
</html>