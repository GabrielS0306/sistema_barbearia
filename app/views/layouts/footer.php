</main>

    <!-- Container de toasts -->
    <div id="toast-container" class="fixed bottom-6 right-6 flex flex-col gap-3 z-50 max-w-sm"></div>

    <footer class="text-center text-gray-600 text-sm py-6 border-t border-gray-800 mt-auto">
        Sistema de Barbearia &copy; <?= date('Y') ?>
    </footer>

    <?php $script = $script ?? null; ?>
    <script src="/barbearia/public/assets/js/utils.js"></script>
    <script src="/barbearia/public/assets/js/toast.js"></script>
    <?php if (!empty($script)): ?>
        <script src="/barbearia/public/assets/js/<?= $script ?>"></script>
    <?php endif; ?>
    <?php if (isset($_SESSION['user_id'])): ?>
        <script src="/barbearia/public/assets/js/inatividade.js"></script>
        <script src="/barbearia/public/assets/js/notificacoes.js"></script>
    <?php endif; ?>

    <?php if (!empty($_SESSION['sucesso'])): ?>
    <script>
        mostrarToast(<?= json_encode($_SESSION['sucesso']) ?>, 'sucesso');
    </script>
    <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['erro'])): ?>
    <script>
        mostrarToast(<?= json_encode($_SESSION['erro']) ?>, 'erro');
    </script>
    <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

</body>
</html>