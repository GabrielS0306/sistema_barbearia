<?php
// app/views/admin/horarios.php
$horarios = $horarios ?? [];
$titulo   = 'Horários de Funcionamento';
require __DIR__ . '/../layouts/header.php';

$dias = [
    0 => 'Domingo',
    1 => 'Segunda-feira',
    2 => 'Terça-feira',
    3 => 'Quarta-feira',
    4 => 'Quinta-feira',
    5 => 'Sexta-feira',
    6 => 'Sábado',
];
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-amber-400">Horários de Funcionamento</h1>
    <p class="text-gray-400 mt-1">Configure os dias e horários que a barbearia atende.</p>
</div>

<form action="/barbearia/admin/horarios" method="POST"
    class="bg-gray-900 border border-gray-800 rounded-xl p-6">
    <?= Csrf::campo() ?>

    <div class="flex flex-col gap-4">
        <?php foreach ($horarios as $i => $h): ?>
            <div class="flex items-center gap-4 border-b border-gray-800 pb-4">
                <input type="hidden" name="id[]" value="<?= $h['id'] ?>">

                <div class="w-36">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="ativo[<?= $i ?>]" value="1"
                            <?= $h['ativo'] ? 'checked' : '' ?>
                            class="accent-amber-400 w-4 h-4">
                        <span class="font-medium text-sm"><?= $dias[$h['dia_semana']] ?></span>
                    </label>
                </div>

                <div class="flex items-center gap-2 flex-1">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-gray-400">Abertura</label>
                        <input type="time" name="hora_inicio[]"
                            value="<?= substr($h['hora_inicio'], 0, 5) ?>"
                            class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-1.5 text-gray-100 text-sm focus:outline-none focus:border-amber-400">
                    </div>

                    <span class="text-gray-500 mt-4">até</span>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-gray-400">Fechamento</label>
                        <input type="time" name="hora_fim[]"
                            value="<?= substr($h['hora_fim'], 0, 5) ?>"
                            class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-1.5 text-gray-100 text-sm focus:outline-none focus:border-amber-400">
                    </div>
                </div>

                <?php if (!$h['ativo']): ?>
                    <span class="text-red-400 text-xs">Fechado</span>
                <?php else: ?>
                    <span class="text-green-400 text-xs">Aberto</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-6">
        <button type="submit"
            class="bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold px-6 py-2 rounded-lg transition">
            Salvar Horários
        </button>
    </div>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>