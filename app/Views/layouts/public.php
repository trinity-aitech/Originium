<?php require BASE_DIR . '/app/Views/partials/head.php'; ?>

<header class="relative z-30">
    <nav class="mx-auto max-w-6xl px-6 h-20 flex items-center justify-between">
        <?php require BASE_DIR . '/app/Views/partials/brand.php'; ?>

        <div class="hidden md:flex items-center gap-9 text-sm font-medium text-zinc-600 dark:text-zinc-400">
            <a href="<?= url('/#recursos') ?>" class="hover:text-zinc-900 dark:hover:text-white transition">Recursos</a>
            <a href="<?= url('/#faq') ?>" class="hover:text-zinc-900 dark:hover:text-white transition">FAQ</a>
        </div>

        <div class="flex items-center gap-3">
            <?php require BASE_DIR . '/app/Views/partials/theme-toggle.php'; ?>
            <?php if (\App\Core\Auth::check()): ?>
                <a href="<?= url('dashboard') ?>" class="text-sm font-medium px-5 py-2.5 rounded-full bg-orange-600 text-white hover:bg-orange-500 transition">Dashboard</a>
            <?php else: ?>
                <a href="<?= url('login') ?>" class="hidden sm:inline text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition">Entrar</a>
                <a href="<?= url('register') ?>" class="text-sm font-medium px-5 py-2.5 rounded-full bg-orange-600 text-white hover:bg-orange-500 transition">Criar conta</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<?= $content ?>

<footer class="border-t border-black/5 dark:border-white/5 mt-28">
    <div class="mx-auto max-w-6xl px-6 py-14 grid gap-10 md:grid-cols-4">
        <div class="md:col-span-2">
            <?php require BASE_DIR . '/app/Views/partials/brand.php'; ?>
            <p class="mt-4 text-sm text-zinc-500 max-w-xs leading-relaxed">
                Reúna todos os seus links em uma página pública minimalista e elegante. Feito em PHP, sem complicação.
            </p>
        </div>
        <div>
            <h4 class="text-sm font-semibold text-zinc-900 dark:text-white mb-3">Produto</h4>
            <ul class="space-y-2.5 text-sm text-zinc-500">
                <li><a href="<?= url('/#recursos') ?>" class="hover:text-zinc-900 dark:hover:text-zinc-200 transition">Recursos</a></li>
                <li><a href="<?= url('/#faq') ?>" class="hover:text-zinc-900 dark:hover:text-zinc-200 transition">FAQ</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-sm font-semibold text-zinc-900 dark:text-white mb-3">Conta</h4>
            <ul class="space-y-2.5 text-sm text-zinc-500">
                <li><a href="<?= url('login') ?>" class="hover:text-zinc-900 dark:hover:text-zinc-200 transition">Entrar</a></li>
                <li><a href="<?= url('register') ?>" class="hover:text-zinc-900 dark:hover:text-zinc-200 transition">Criar conta</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-black/5 dark:border-white/5">
        <div class="mx-auto max-w-6xl px-6 py-6 text-xs text-zinc-500">
            © <?= date('Y') ?> Originium. Todos os direitos reservados.
        </div>
    </div>
</footer>

<?php require BASE_DIR . '/app/Views/partials/foot.php'; ?>
