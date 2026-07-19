<?php

    // app/views/agendamento/lista.php
    /** @var Paginacao $paginacao */
    /** @var array $filtros */
    /** @var int $total */
    $agendamentos = $agendamentos ?? [];
    $paginacao = $paginacao ?? null;
    $total     = $total ?? 0;
    $titulo = 'Meus Agendamentos';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="flex items-center justify-between mb-8">
    <h1 class="text-3xl font-bold text-amber-400">Meus Agendamentos</h1>

    <a href="/barbearia/agendamento/novo"
        class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-4 py-2 rounded-lg transition text-sm">
        + Novo Agendamento
    </a>
</div>

<?php if (empty($agendamentos)): ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center text-gray-500">
        Você ainda não tem agendamentos.
    </div>
<?php else: ?>
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Data</th>
                    <th class="px-6 py-3 text-left">Horário</th>
                    <th class="px-6 py-3 text-left">Barbeiro</th>
                    <th class="px-6 py-3 text-left">Serviço</th>
                    <th class="px-6 py-3 text-left">Preço</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Comprovante</th>
                    <th class="px-6 py-3 text-left">Pagamento</th>
                    <th class="px-6 py-3 text-left">Ação</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-800">
                <?php foreach ($agendamentos as $ag): ?>
                    <?php
                        $statusClasses = match($ag['status']) {
                            'confirmado' => 'bg-green-900 text-green-300',
                            'concluido'  => 'bg-blue-900 text-blue-300',
                            'cancelado'  => 'bg-red-900 text-red-300',
                            default      => 'bg-yellow-900 text-yellow-300',
                        };
                    ?>
                    <tr class="hover:bg-gray-800 transition">
                        <td class="px-6 py-4">
                            <?= date('d/m/Y', strtotime($ag['data'])) ?>
                        </td>

                        <td class="px-6 py-4">
                            <?= $ag['hora'] ?>
                        </td>
                        <td class="px-6 py-4 font-medium">
                            <?= htmlspecialchars($ag['barbeiro']) ?>
                        </td>

                        <td class="px-6 py-4 text-gray-400">
                            <?= htmlspecialchars($ag['servico']) ?>
                        </td>
                        <td class="px-6 py-4 text-amber-400">
                            R$ <?= number_format($ag['preco'], 2, ',', '.') ?>
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?= $statusClasses ?>">
                                <?= htmlspecialchars($ag['status']) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <?php
                                $pagamentoClasses = match($ag['status_pagamento'] ?? 'pendente') {
                                    'pago'      => 'bg-green-900 text-green-300',
                                    'cancelado' => 'bg-red-900 text-red-300',
                                    default     => 'bg-yellow-900 text-yellow-300',
                                };

                                $formaPagamento = match($ag['forma_pagamento'] ?? 'dinheiro') {
                                    'pix'    => '📱 PIX',
                                    'cartao' => '💳 Cartão',
                                    default  => '💵 Dinheiro',
                                };
                            ?>
                            <div class="flex flex-col gap-1">
                                <span class="px-2 py-1 rounded-full text-xs font-medium <?= $pagamentoClasses ?> w-fit">
                                    <?= ucfirst($ag['status_pagamento'] ?? 'pendente') ?>
                                </span>

                                <span class="text-gray-500 text-xs">
                                    <?= $formaPagamento ?>
                                </span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <a href="/barbearia/agendamento/comprovante?id=<?= $ag['id'] ?>" target="_blank" class="text-amber-400 hover:underline text-sm">
                                PDF
                            </a>
                        </td>

                        <td class="px-6 py-4">
                            <?php if (!in_array($ag['status'], ['concluido', 'cancelado']) && $ag['data'] >= date('Y-m-d')): ?>
                                <div class="flex gap-3">
                                    <form action="/barbearia/agendamento/adiar" method="POST">
                                        <?= Csrf::campo() ?>

                                        <input type="hidden" name="id" value="<?= $ag['id'] ?>">

                                        <button type="submit" class="text-amber-400 hover:underline text-sm">
                                            Adiar
                                        </button>
                                    </form>

                                    <form action="/barbearia/agendamento/cancelar" method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar este agendamento?');">
                                        <?= Csrf::campo() ?>
                                        
                                        <input type="hidden" name="id" value="<?= $ag['id'] ?>">

                                        <button type="submit" class="text-red-400 hover:underline text-sm">
                                            Cancelar
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-600 text-sm">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Paginação -->
        <?php if ($paginacao->totalPaginas() > 1): ?>
            <div class="flex items-center justify-between mt-6 border-t border-gray-600 p-5">
                <p class="text-gray-400 text-sm">
                    Página <?= $paginacao->paginaAtual() ?> de <?= $paginacao->totalPaginas() ?>
                    (<?= $total ?> agendamentos no total)
                </p>

                <div class="flex gap-2">
                    <?php if ($paginacao->temAnterior()): ?>
                        <a href="?pagina=<?= $paginacao->paginaAnterior() ?>"
                            class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-3 py-2 rounded-lg transition text-sm">
                            ← Anterior
                        </a>
                    <?php endif; ?>

                    <?php foreach ($paginacao->paginas() as $pagina): ?>
                        <a href="?pagina=<?= $pagina ?>"
                            class="<?= $pagina === $paginacao->paginaAtual()
                                ? 'bg-amber-400 text-gray-950'
                                : 'bg-gray-800 hover:bg-gray-700 text-gray-300' ?> px-3 py-2 rounded-lg transition text-sm font-medium">
                            <?= $pagina ?>
                        </a>
                    <?php endforeach; ?>

                    <?php if ($paginacao->temProxima()): ?>
                        <a href="?pagina=<?= $paginacao->proximaPagina() ?>"
                            class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-3 py-2 rounded-lg transition text-sm">
                            Próximo →
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>