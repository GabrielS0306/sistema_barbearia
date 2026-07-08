<?php 

    // app/views/auth/register.php
    $titulo = "Cadastro";
    $script = "register.js";
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
    
    <form id="form-register" action="/barbearia/register" method="POST" novalidate class="bg-gray-900 rounded-xl p-8 flex flex-col gap-3 border border-gray-800">
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
            <div class="relative">
                <input type="password" id="senha" name="senha" required
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400 pr-10">
                <button type="button" onclick="toggleSenha('senha', 'icone-senha')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-amber-400 transition">
                    <svg id="icone-senha" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-1">
            <label for="confirmar_senha" class="text-sm text-gray-400">Confirmar Senha</label>
            <div class="relative">
                <input type="password" id="confirmar_senha" name="confirmar_senha" required
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400 pr-10">
                <button type="button" onclick="toggleSenha('senha', 'icone-senha')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-amber-400 transition">
                    <svg id="icone-senha" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
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