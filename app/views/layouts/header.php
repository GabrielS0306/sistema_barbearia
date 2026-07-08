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
    <!-- tailwindcss -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">    
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col w-full overflow-x-hidden">
    <nav class="bg-gray-900 border-b border-gray-800 px-6 py-4 w-full">
        <div class="flex items-center justify-between">
            <span class="text-amber-400 font-bold text-xl">✂ Barbearia</span>

            <!-- Botão hambúrguer (só no mobile) -->
            <?php if (isset($_SESSION['user_role'])): ?>
                <button id="menu-toggle" class="md:hidden text-gray-300 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="icon-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"/>
                        <path id="icon-close" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" class="hidden"/>
                    </svg>
                </button>
            <?php endif; ?>

            <!-- Links desktop (escondido no mobile) -->
            <div class="hidden md:flex gap-4 text-sm">
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
        </div>

        <!-- Menu mobile (escondido por padrão) -->
        <?php if (isset($_SESSION['user_role'])): ?>
            <div id="menu-mobile" class="hidden md:hidden mt-4 flex flex-col gap-3 text-sm border-t border-gray-800 pt-4">
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
            </div>
        <?php endif; ?>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8 w-full">
</body>
</html>