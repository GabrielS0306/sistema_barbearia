<?php

    // app/views/cliente/perfil.php
    $cliente = $cliente ?? [];
    $titulo  = 'Meu Perfil';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="max-w-lg mx-auto">
    <h1 class="text-3xl font-bold text-amber-400 mb-8">Meu Perfil</h1>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($_SESSION['sucesso']) ?>

            <?php unset($_SESSION['sucesso']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($_SESSION['erro']) ?>

            <?php unset($_SESSION['erro']); ?>
        </div>
    <?php endif; ?>

    <form action="/barbearia/cliente/perfil" method="POST"
        class="bg-gray-900 border border-gray-800 rounded-xl p-8 flex flex-col gap-5">
        <?= Csrf::campo() ?>

        <div class="flex flex-col gap-1">
            <label for="email" class="text-sm text-gray-400">Email</label>

            <input type="email" id="email" value="<?= htmlspecialchars($cliente['email'] ?? '') ?>" disabled class="bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-gray-400 cursor-not-allowed">

            <span class="text-xs text-gray-500">O email não pode ser alterado.</span>
        </div>

        <div class="flex flex-col gap-1">
            <label for="nome" class="text-sm text-gray-400">Nome completo</label>

            <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($cliente['nome'] ?? '') ?>"
                class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
        </div>

        <div class="flex flex-col gap-1">
            <label for="telefone" class="text-sm text-gray-400">Telefone</label>

            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone'] ?? '') ?>"
                placeholder="(00) 00000-0000"
                class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
        </div>

        <button type="submit" class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold py-2 rounded-lg transition mt-2">
            Salvar Alterações
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="/barbearia/agendamento/meus" class="text-amber-400 hover:underline text-sm">
            ← Voltar para meus agendamentos
        </a>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>