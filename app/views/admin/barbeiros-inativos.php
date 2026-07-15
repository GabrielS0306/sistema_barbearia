<?php

    // app/views/admin/barbeiros-inativos.php
    $barbeiros = $barbeiros ?? [];
    $titulo    = 'Barbeiros Inativos';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-amber-400">Barbeiros Inativos</h1>

        <p class="text-gray-400 text-sm mt-1">Barbeiros desativados do sistema</p>
    </div>

    <a href="/barbearia/admin/barbeiros" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-4 py-2 rounded-lg transition text-sm">
        ← Voltar
    </a>
</div>

<?php if (isset($_SESSION['sucesso'])): ?>
    <div class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded mb-6">
        <?= htmlspecialchars($_SESSION['sucesso']) ?>
        
        <?php unset($_SESSION['sucesso']); ?>
    </div>
<?php endif; ?>

<?php if (empty($barbeiros)): ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center text-gray-500">
        Nenhum barbeiro inativo.
    </div>
<?php else: ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Foto</th>
                    <th class="px-6 py-3 text-left">Nome</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Especialidade</th>
                    <th class="px-6 py-3 text-left">Ação</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-800">
                <?php foreach ($barbeiros as $barbeiro): ?>
                    <tr class="hover:bg-gray-800 transition opacity-60">
                        <td class="px-6 py-4">
                            <?php if (!empty($barbeiro['foto'])): ?>
                                <img src="/barbearia/public/uploads/barbeiros/<?= htmlspecialchars($barbeiro['foto']) ?>"
                                    alt="Foto" class="w-10 h-10 rounded-full object-cover grayscale">
                            <?php else: ?>
                                <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 text-lg">
                                    👤
                                </div>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 font-medium">
                            <?= htmlspecialchars($barbeiro['nome']) ?>
                        </td>

                        <td class="px-6 py-4 text-gray-400">
                            <?= htmlspecialchars($barbeiro['email']) ?>
                        </td>

                        <td class="px-6 py-4 text-gray-400">
                            <?= htmlspecialchars($barbeiro['especialidade'] ?? '') ?>
                        </td>

                        <td class="px-6 py-4">
                            <form action="/barbearia/admin/barbeiros/reativar" method="POST" onsubmit="return confirm('Reativar este barbeiro?');">
                                <input type="hidden" name="id" value="<?= $barbeiro['id'] ?>">

                                <button type="submit" class="text-green-400 hover:underline text-sm">
                                    Reativar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>