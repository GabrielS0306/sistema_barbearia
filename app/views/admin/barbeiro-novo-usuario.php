<?php

    // app/views/admin/barbeiro-novo-usuario.php
    $titulo = 'Novo Barbeiro';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="max-w-lg mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-amber-400">Novo Barbeiro</h1>

        <p class="text-gray-400 mt-1">Passo 1 de 2 — Criar conta de acesso</p>
    </div>

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($_SESSION['erro']) ?>
            <?php unset($_SESSION['erro']); ?>
        </div>
    <?php endif; ?>

    <form action="/barbearia/admin/barbeiros/novo-usuario" method="POST" 
        class="bg-gray-900 border border-gray-800 rounded-xl p-8 flex flex-col gap-5">

        <div class="flex flex-col gap-1">
            <label for="email" class="text-sm text-gray-400">Email</label>

            <input type="email" id="email" name="email" required class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
        </div>

        <div class="flex flex-col gap-1">
            <label for="senha" class="text-sm text-gray-400">Senha</label>

            <input type="password" id="senha" name="senha" required
                class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
        </div>

        <div class="flex gap-3 mt-2">
            <button type="submit" class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-6 py-2 rounded-lg transition">
                Continuar →
            </button>

            <a href="/barbearia/admin/barbeiros" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-6 py-2 rounded-lg transition">
                Cancelar
            </a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>