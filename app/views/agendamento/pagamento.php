<?php

    $barbeiro = $barbeiro ?? [];
    $servico  = $servico ?? [];
    $dados    = $dados ?? [];
    $titulo   = 'Forma de Pagamento';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="max-w-lg mx-auto">
    <h1 class="text-3xl font-bold text-amber-400 mb-2">Forma de Pagamento</h1>
    <p class="text-gray-400 mb-8">Escolha como deseja pagar pelo serviço.</p>

    <!-- Resumo do agendamento -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-6">
        <h2 class="text-sm text-gray-400 uppercase mb-3">Resumo do agendamento</h2>
        <div class="flex flex-col gap-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Barbeiro</span>
                <span class="font-medium">
                    <?= htmlspecialchars($barbeiro['nome']) ?>
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-400">Serviço</span>
                <span class="font-medium">
                    <?= htmlspecialchars($servico['nome']) ?>
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-400">Data</span>
                <span class="font-medium">
                    <?= date('d/m/Y', strtotime($dados['data'])) ?>
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-400">Horário</span>
                <span class="font-medium">
                    <?= substr($dados['hora'], 0, 5) ?>
                </span>
            </div>
            
            <div class="flex justify-between border-t border-gray-800 pt-2 mt-1">
                <span class="text-gray-400">Total</span>
                <span class="font-bold text-amber-400">
                    R$ <?= number_format($servico['preco'], 2, ',', '.') ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Seleção de forma de pagamento -->
    <form action="/barbearia/agendamento/confirmar-pagamento" method="POST">
        <div class="flex flex-col gap-3 mb-6">

            <!-- Dinheiro -->
            <label class="flex items-center gap-4 bg-gray-900 border border-gray-800 rounded-xl p-4 cursor-pointer hover:border-amber-400 transition has-[:checked]:border-amber-400">
                <input type="radio" name="forma_pagamento" value="dinheiro" checked class="accent-amber-400">
                <div>
                    <p class="font-medium">💵 Dinheiro</p>
                    <p class="text-gray-500 text-xs mt-1">Pagamento presencial no momento do atendimento</p>
                </div>
            </label>

            <!-- PIX -->
            <label class="flex items-center gap-4 bg-gray-900 border border-gray-800 rounded-xl p-4 cursor-pointer hover:border-amber-400 transition has-[:checked]:border-amber-400">
                <input type="radio" name="forma_pagamento" value="pix" class="accent-amber-400">
                <div>
                    <p class="font-medium">📱 PIX</p>
                    <p class="text-gray-500 text-xs mt-1">Pague via PIX antes do atendimento</p>
                </div>
            </label>

            <!-- Cartão -->
            <label class="flex items-center gap-4 bg-gray-900 border border-gray-800 rounded-xl p-4 cursor-pointer hover:border-amber-400 transition has-[:checked]:border-amber-400 opacity-50">
                <input type="radio" name="forma_pagamento" value="cartao" disabled class="accent-amber-400">
                <div>
                    <p class="font-medium">💳 Cartão de crédito/débito</p>
                    <p class="text-gray-500 text-xs mt-1">Em breve — integração com Mercado Pago</p>
                </div>
            </label>

        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="flex-1 bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold py-3 rounded-lg transition">
                Confirmar Agendamento
            </button>
            <a href="/barbearia/agendamento/novo"
                class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-6 py-3 rounded-lg transition">
                Voltar
            </a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>