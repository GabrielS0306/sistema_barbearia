<?php 

    // app/views/layouts/header.php
    $titulo = $titulo ?? 'Sistema de Barbearia';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo); ?> - Barbearia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col">
    <nav class="bg-gray-900 border-b border-gray-800 px-6 py-4 flex items-center justify-between">
        <span class="text-amber-400 font-bold text-xl">✂ Barbearia</span>

        <div class="flex gap-4 text-sm">
            <?php if (isset($_SESSION['user_role'])): ?>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="/barbearia/admin/dashboard" class="hover:text-amber-400 transition">Dashboard</a>
                    <a href="/barbearia/admin/servicos" class="hover:text-amber-400 transition">Serviços</a>
                    <a href="/barbearia/admin/barbeiros" class="hover:text-amber-400 transition">Barbeiros</a>
                <?php elseif ($_SESSION['user_role'] === 'barbeiro'): ?>
                    <a href="/barbearia/barbeiro/agenda" class="hover:text-amber-400 transition">Minha Agenda</a>
                <?php else: ?>
                    <a href="/barbearia/agendamento/novo" class="hover:text-amber-400 transition">Agendar</a>
                    <a href="/barbearia/agendamento/meus" class="hover:text-amber-400 transition">Meus Agendamentos</a>
                <?php endif; ?>
                <a href="/barbearia/logout" class="text-red-400 hover:text-red-300 transition">Sair</a>
            <?php endif; ?>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8">
</body>
</html>