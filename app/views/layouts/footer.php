    </main>

    <footer class="text-center text-gray-600 text-sm py-6 border-t border-gray-800 mt-auto">
        Sistema de Barbearia &copy; <?= date('Y') ?>
    </footer>

    <?php $script = $script ?? null; ?>
    <script src="/barbearia/public/assets/js/utils.js"></script>    
    <?php if (!empty($script)): ?>
        <script src="/barbearia/public/assets/js/<?= $script ?>"></script>
    <?php endif; ?>
</body>
</html>