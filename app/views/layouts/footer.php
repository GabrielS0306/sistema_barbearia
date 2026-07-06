    </main>

    <footer class="text-center text-gray-600 text-sm py-6 border-t border-gray-800 mt-auto">
        Sistema de Barbearia &copy; <?= date('Y') ?>
    </footer>

    <script>
        const toggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('menu-mobile');
        const iconOpen = document.getElementById('icon-open');
        const iconClose = document.getElementById('icon-close');

        if (toggle) {
            toggle.addEventListener('click', function () {
                if (menu.classList.contains('hidden')) {
                    menu.classList.remove('hidden');
                    menu.style.maxHeight = '0px';
                    menu.style.overflow = 'hidden';
                    menu.style.transition = 'max-height 0.3s ease';
                    setTimeout(() => {
                        menu.style.maxHeight = menu.scrollHeight + 'px';
                    }, 10);
                } else {
                    menu.style.maxHeight = '0px';
                    setTimeout(() => {
                        menu.classList.add('hidden');
                        menu.style.maxHeight = '';
                    }, 300);
                }

                iconOpen.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');
            });
        }
    </script>

    <?php $script = $script ?? null; ?>
    <script src="/barbearia/public/assets/js/utils.js"></script>    
    <?php if (!empty($script)): ?>
        <script src="/barbearia/public/assets/js/<?= $script ?>"></script>
    <?php endif; ?>
</body>
</html>