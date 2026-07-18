<?php

    // app/views/admin/barbeiro-novo-perfil.php
    $usuariosDisponiveis = $usuariosDisponiveis ?? [];
    $titulo = 'Novo Barbeiro - Perfil';
    $script = 'barbeiro.js';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="max-w-lg mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-amber-400">Novo Barbeiro</h1>

        <p class="text-gray-400 mt-1">Passo 2 de 2 — Completar perfil profissional</p>
    </div>

    <?php if (empty($usuariosDisponiveis)): ?>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center">
            <p class="text-gray-400 mb-4">Não há contas pendentes de vínculo.</p>

            <a href="/barbearia/admin/barbeiros/novo-usuario"
                class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-6 py-2 rounded-lg transition">
                Criar nova conta
            </a>
        </div>
    <?php else: ?>
        <form id="form-barbeiro-perfil" action="/barbearia/admin/barbeiros/novo-perfil" method="POST" enctype="multipart/form-data" class="bg-gray-900 border border-gray-800 rounded-xl p-8 flex flex-col gap-5" novalidate>
            <?= Csrf::campo() ?>

            <div class="flex flex-col gap-1">
                <label for="usuario_id" class="text-sm text-gray-400">Conta vinculada</label>
                <select id="usuario_id" name="usuario_id" required
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
                    <?php foreach ($usuariosDisponiveis as $usuario): ?>
                        <option value="<?= $usuario['id'] ?>">
                            <?= htmlspecialchars($usuario['email']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label for="nome" class="text-sm text-gray-400">Nome de exibição</label>

                <input type="text" id="nome" name="nome" required
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
            </div>

            <div class="flex flex-col gap-1">
                <label for="especialidade" class="text-sm text-gray-400">Especialidade</label>

                <input type="text" id="especialidade" name="especialidade"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
            </div>

            <div class="flex flex-col gap-1">
                <label for="foto" class="text-sm text-gray-400">Foto (jpg, png, webp — máx 2MB)</label>

                <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:bg-amber-400 file:text-gray-950 file:font-bold file:cursor-pointer">
            </div>

            <div class="flex gap-3 mt-2">
                <button type="submit"
                    class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-6 py-2 rounded-lg transition">
                    Salvar Perfil
                </button>

                <a href="/barbearia/admin/barbeiros"
                    class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-6 py-2 rounded-lg transition">
                    Cancelar
                </a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>