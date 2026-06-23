<?php

    // app/views/admin/servicos.php
    $servicos = $servicos ?? [];
    $titulo = 'Serviços';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="flex items-center justify-between mb-8">
    <h1 class="text-3xl font-bold text-amber-400">Serviços</h1>

    <a 
        href="/barbearia/admin/servicos/novo"
        class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-4 py-2 rounded-lg transition text-sm"
    >
        + Novo Serviço
    </a>
</div>

<?php if (empty($servicos)): ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center text-gray-500">
        Nenhum serviço cadastrado ainda.
    </div>
<?php else: ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Nome</th>
                    <th class="px-6 py-3 text-left">Descrição</th>
                    <th class="px-6 py-3 text-left">Preço</th>
                    <th class="px-6 py-3 text-left">Duração</th>
                    <th class="px-6 py-3 text-left">Ações</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-800">
                <?php foreach ($servicos as $servico): ?>
                    <tr class="hover:bg-gray-800 transition">
                        <td class="px-6 py-4 font-medium">
                            <?= htmlspecialchars($servico['nome']) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-400">
                            <?= htmlspecialchars($servico['descricao']) ?>
                        </td>
                        <td class="px-6 py-4 text-amber-400 font-medium">
                            R$ <?= number_format($servico['preco'], 2, ',', '.') ?>
                        </td>
                        <td class="px-6 py-4 text-gray-400">
                            <?= $servico['duracao_min'] ?>min
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                                <a href="/barbearia/admin/servicos/editar?id=<?= $servico['id'] ?>"
                                    class="text-amber-400 hover:underline text-sm">Editar</a>

                                <form 
                                    action="/barbearia/admin/servicos/deletar" 
                                    method="POST" 
                                    onsubmit="return confirm('Tem certeza que deseja excluir este serviço?');"
                                >
                                    <input type="hidden" name="id" value="<?= $servico['id'] ?>">
                                    <button type="submit" class="text-red-400 hover:underline text-sm">Excluir</button>
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