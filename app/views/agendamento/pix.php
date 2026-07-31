<?php

    // app/views/agendamento/pix.php
    $servico   = $servico ?? [];
    $qrCode    = $qrCode ?? '';
    $copiaCola = $copiaCola ?? '';
    $titulo    = 'Pagamento via PIX';
    require __DIR__ . '/../layouts/header.php';

?>

<div class="max-w-lg mx-auto">
    <h1 class="text-3xl font-bold text-amber-400 mb-2">Pagamento via PIX</h1>
    <p class="text-gray-400 mb-8">Escaneie o QR Code ou copie o código para pagar.</p>

    <!-- Valor -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-6 text-center">
        <p class="text-gray-400 text-sm mb-1">Valor a pagar</p>
        <p class="text-4xl font-bold text-amber-400">
            R$ <?= number_format($servico['preco'], 2, ',', '.') ?>
        </p>
        <p class="text-gray-500 text-sm mt-1"><?= htmlspecialchars($servico['nome']) ?></p>
    </div>

    <!-- QR Code -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6 text-center">
        <p class="text-gray-400 text-sm mb-4">Escaneie com o app do seu banco</p>
        <img src="<?= $qrCode ?>" alt="QR Code PIX" class="mx-auto w-48 h-48 rounded-lg">
    </div>

    <!-- Copia e cola -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-6">
        <p class="text-gray-400 text-sm mb-2">Ou copie o código PIX</p>
        <div class="flex gap-2">
            <input type="text" id="codigo-pix" readonly
                value="<?= htmlspecialchars($copiaCola) ?>"
                class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-300 text-xs focus:outline-none">
            <button onclick="copiarPix()"
                class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-4 py-2 rounded-lg transition text-sm whitespace-nowrap">
                Copiar
            </button>
        </div>
        <p id="msg-copiado" class="text-green-400 text-xs mt-2 hidden">✓ Código copiado!</p>
    </div>

    <!-- Instruções -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 mb-6">
        <p class="text-gray-400 text-sm font-medium mb-3">Como pagar:</p>
        <ol class="flex flex-col gap-2 text-sm text-gray-400">
            <li>1. Abra o app do seu banco</li>
            <li>2. Escolha pagar via PIX</li>
            <li>3. Escaneie o QR Code ou cole o código</li>
            <li>4. Confirme o pagamento</li>
            <li>5. Clique em "Já paguei" abaixo</li>
        </ol>
    </div>

    <!-- Botão de confirmação -->
    <form action="/barbearia/agendamento/pix" method="POST">
        <?= Csrf::campo() ?>
        <button type="submit"
            class="w-full bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold py-3 rounded-lg transition">
            ✓ Já realizei o pagamento
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="/barbearia/agendamento/pagamento"
            class="text-gray-500 hover:text-gray-300 text-sm">
            ← Voltar e escolher outra forma de pagamento
        </a>
    </div>
</div>

<script>
    function copiarPix() {
        const input = document.getElementById('codigo-pix');
        const msg   = document.getElementById('msg-copiado');
        
        navigator.clipboard.writeText(input.value).then(() => {
            msg.classList.remove('hidden');
            setTimeout(() => msg.classList.add('hidden'), 3000);
        });
    }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>