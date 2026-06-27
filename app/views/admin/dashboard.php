<?php

    // app/views/admin/dashboard.php
    $titulo = 'Dashboard';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-amber-400">Painel Administrativo</h1>
    <p class="text-gray-400 mt-1">Gerencie o sistema da barbearia</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="/barbearia/admin/servicos"
        class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-amber-400 transition group">
        <div class="text-amber-400 text-3xl mb-3">
            ✂
        </div>

        <h2 class="text-xl font-bold group-hover:text-amber-400 transition">Serviços</h2>

        <p class="text-gray-500 text-sm mt-1">Gerencie os serviços oferecidos</p>
    </a>

    <a href="/barbearia/admin/barbeiros"
        class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-amber-400 transition group">
        <div class="text-amber-400 text-3xl mb-3">
            👤
        </div>

        <h2 class="text-xl font-bold group-hover:text-amber-400 transition">Barbeiros</h2>

        <p class="text-gray-500 text-sm mt-1">Gerencie a equipe de barbeiros</p>
    </a>

    <a href="/barbearia/admin/dashboard" 
        class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-amber-400 transition group opacity-50 cursor-not-allowed">
        <div class="text-amber-400 text-3xl mb-3">📅</div>

        <h2 class="text-xl font-bold group-hover:text-amber-400 transition">Agendamentos</h2>

        <p class="text-gray-500 text-sm mt-1">Em breve</p>
    </a>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>