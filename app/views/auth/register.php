<?php 

    // app/views/auth/register.php
    $itulo = "Cadastro";
    require __DIR__ . "/../layouts/header.php";

?>

<div class="max-w-md mx-auto">
    <h1 class="text-3xl font-bold text-amber-400 mb-5 text-center">Criar Conta</h1>

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($_SESSION['erro']) ?>
            <?php unset($_SESSION['erro']); ?>
        </div>
    <?php endif; ?>
    
    <form action="/barbearia/register" method="POST" class="bg-gray-900 rounded-xl p-8 flex flex-col gap-3 border border-gray-800">
        <div class="flex flex-col gap-1">
            <label for="nome" class="text-sm text-gray-400">Nome completo</label>
            <input type="text" id="nome" name="nome" required
                class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-1 text-gray-100 focus:outline-none focus:border-amber-400">
        </div>

        <div class="flex flex-col gap-1">
            <label for="email" class="text-sm text-gray-400">Email</label>
            <input type="email" id="email" name="email" required
                class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-1 text-gray-100 focus:outline-none focus:border-amber-400">
        </div>

        <div class="flex flex-col gap-1">
            <label for="senha" class="text-sm text-gray-400">Senha</label>
            <input type="password" id="senha" name="senha" required
                class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-1 text-gray-100 focus:outline-none focus:border-amber-400">
        </div>

        <div class="flex flex-col gap-1">
            <label for="senha" class="text-sm text-gray-400">Confirmar senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required
                class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-1 text-gray-100 focus:outline-none focus:border-amber-400">
        </div>

        <button type="submit"
            class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold py-1 rounded-lg transition mt-2">
            Cadastrar
        </button>
    </form>

    <p class="text-center text-gray-500 text-sm mt-4">
        Já tem conta? 
        <a href="/barbearia/login" class="text-amber-400 hover:underline">Entrar</a>
    </p>
</div>

<?php require __DIR__ . "/../layouts/footer.php"; ?>