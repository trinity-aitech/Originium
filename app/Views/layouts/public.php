<?php $bodyClass = 'bg-ink text-zinc-200'; require BASE_DIR . '/app/Views/partials/head.php'; ?>

<header class="relative z-20">
    <nav class="mx-auto max-w-6xl px-6 h-16 flex items-center justify-between">
        <?php require BASE_DIR . '/app/Views/partials/brand.php'; ?>

        <div class="hidden md:flex items-center gap-8 text-sm text-zinc-400">
            <a href="<?= url('/#recursos') ?>" class="hover:text-white transition">Recursos</a>
            <a href="<?= url('/#faq') ?>" class="hover:text-white transition">FAQ</a>
        </div>

        <div class="flex items-center gap-3">
            <?php if (\App\Core\Auth::check()): ?>
                <a href="<?= url('dashboard') ?>" class="text-sm px-4 py-2 rounded-full glass hover:bg-white/10 transition">Dashboard</a>
            <?php else: ?>
                <a href="<?= url('login') ?>" class="text-sm text-zinc-300 hover:text-white transition">Entrar</a>
                <a href="<?= url('register') ?>" class="text-sm px-4 py-2 rounded-full bg-white text-ink font-medium hover:bg-zinc-200 transition">Criar conta</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<?= $content ?>

<footer class="border-t border-white/5 mt-24">
    <div class="mx-auto max-w-6xl px-6 py-12 grid gap-8 md:grid-cols-4">
        <div class="md:col-span-2">
            <?php require BASE_DIR . '/app/Views/partials/brand.php'; ?>
            <p class="mt-4 text-sm text-zinc-500 max-w-xs">
                Reúna todos os seus links em uma página pública minimalista e elegante. Feito em PHP, sem complicação.
            </p>
        </div>
        <div>
            <h4 class="text-sm font-semibold text-white mb-3">Produto</h4>
            <ul class="space-y-2 text-sm text-zinc-500">
                <li><a href="<?= url('/#recursos') ?>" class="hover:text-zinc-300 transition">Recursos</a></li>
                <li><a href="<?= url('/#faq') ?>" class="hover:text-zinc-300 transition">FAQ</a></li>
                <li><a href="<?= url('register') ?>" class="hover:text-zinc-300 transition">Criar conta</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-sm font-semibold text-white mb-3">Conta</h4>
            <ul class="space-y-2 text-sm text-zinc-500">
                <li><a href="<?= url('login') ?>" class="hover:text-zinc-300 transition">Entrar</a></li>
                <li><a href="<?= url('dashboard') ?>" class="hover:text-zinc-300 transition">Dashboard</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/5">
        <div class="mx-auto max-w-6xl px-6 py-6 text-xs text-zinc-600">
            © <?= date('Y') ?> Originium. Todos os direitos reservados.
        </div>
    </div>
</footer>

<?php require BASE_DIR . '/app/Views/partials/foot.php'; ?>
