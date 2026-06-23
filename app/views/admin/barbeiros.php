<?php
    // app/views/admin/barbeiros.php
    $barbeiros = $barbeiros ?? [];
    $titulo = 'Barbeiros';
    require __DIR__ . '/../layouts/header.php';
?>

<div class="flex items-center justify-between mb-8">
    <h1 class="text-3xl font-bold text-amber-400">Barbeiros</h1>

    <a href="/barbearia/admin/barbeiros/novo-usuario" class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-4 py-2 rounded-lg transition text-sm">
        + Novo Barbeiro
    </a>
</div>

<?php if (empty($barbeiros)): ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center text-gray-500">
        Nenhum barbeiro cadastrado ainda.
    </div>
<?php else: ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Foto</th>
                    <th class="px-6 py-3 text-left">Nome</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Especialidade</th>
                    <th class="px-6 py-3 text-left">Ações</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-800">
                <?php foreach ($barbeiros as $barbeiro): ?>
                    <tr class="hover:bg-gray-800 transition">
                        <td class="px-6 py-4">
                            <?php if (!empty($barbeiro['foto'])): ?>
                                <img src="/barbearia/public/uploads/barbeiros/<?= htmlspecialchars($barbeiro['foto']) ?>"
                                    alt="Foto" class="w-10 h-10 rounded-full object-cover">
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
                            <div class="flex gap-3">
                                <a href="/barbearia/admin/barbeiros/editar?id=<?= $barbeiro['id'] ?>"
                                    class="text-amber-400 hover:underline text-sm">Editar</a>

                                <form action="/barbearia/admin/barbeiros/deletar" method="POST"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este barbeiro?');">
                                    <input type="hidden" name="id" value="<?= $barbeiro['id'] ?>">

                                    <button type="submit" class="text-red-400 hover:underline text-sm">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?> 