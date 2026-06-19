<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
</head>
<body>
    <h1>Painel Administrativo</h1>
    <p>Bem-vindo, <?= htmlspecialchars($_SESSION['user_role']) ?>!</p>
    <nav>
        <a href="/barbearia/admin/barbeiros">Barbeiros</a> |
        <a href="/barbearia/admin/servicos">Serviços</a> |
        <a href="/barbearia/logout">Sair</a>
    </nav>
</body>
</html>