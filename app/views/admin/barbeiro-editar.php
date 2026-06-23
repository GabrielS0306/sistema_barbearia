<?php

    // app/views/admin/barbeiro-editar.php
    $barbeiro = $barbeiro ?? null;
    $titulo = 'Editar Barbeiro';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="max-w-lg mx-auto">
    <h1 class="text-3xl font-bold text-amber-400 mb-8">Editar Barbeiro</h1>

    <?php if ($barbeiro): ?>
        <form action="/barbearia/admin/barbeiros/editar" method="POST"
            enctype="multipart/form-data"
            class="bg-gray-900 border border-gray-800 rounded-xl p-8 flex flex-col gap-5">

            <input type="hidden" name="id" value="<?= $barbeiro['id'] ?>">

            <div class="flex flex-col gap-1">
                <label for="nome" class="text-sm text-gray-400">Nome</label>

                <input type="text" id="nome" name="nome" required
                    value="<?= htmlspecialchars($barbeiro['nome']) ?>"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
            </div>

            <div class="flex flex-col gap-1">
                <label for="especialidade" class="text-sm text-gray-400">Especialidade</label>

                <input type="text" id="especialidade" name="especialidade"
                    value="<?= htmlspecialchars($barbeiro['especialidade'] ?? '') ?>"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-100 focus:outline-none focus:border-amber-400">
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-sm text-gray-400">Foto atual</label>

                <?php if (!empty($barbeiro['foto'])): ?>
                    <img src="/barbearia/public/uploads/barbeiros/<?= htmlspecialchars($barbeiro['foto']) ?>"
                        alt="Foto atual" class="w-20 h-20 rounded-full object-cover">
                <?php else: ?>
                    <div class="w-20 h-20 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 text-2xl">
                        👤
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex flex-col gap-1">
                <label for="foto" class="text-sm text-gray-400">Trocar foto (opcional)</label>

                <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp"
                    class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:bg-amber-400 file:text-gray-950 file:font-bold file:cursor-pointer">
            </div>

            <div class="flex gap-3 mt-2">
                <button type="submit"
                    class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-6 py-2 rounded-lg transition">
                    Salvar Alterações
                </button>

                <a href="/barbearia/admin/barbeiros"
                    class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-6 py-2 rounded-lg transition">
                    Cancelar
                </a>
            </div>
        </form>
    <?php else: ?>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center text-gray-500">
            Barbeiro não encontrado.
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>