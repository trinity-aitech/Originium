<?php
$forceDark = true; // o painel usa tema escuro fixo nesta versão
$bodyClass = 'bg-ink text-zinc-200';
require BASE_DIR . '/app/Views/partials/head.php';

$me = \App\Core\Auth::user();
$current = $_SERVER['REQUEST_URI'] ?? '';
$navGroups = [
    'Principal' => [
        ['dashboard',           'Visão geral'],
        ['dashboard/links',     'Meus links'],
    ],
    'Página pública' => [
        ['dashboard/blueprint',    'Blueprint'],
        ['dashboard/testimonials', 'Depoimentos'],
        ['dashboard/faq',          'FAQ'],
        ['dashboard/timeline',     'Linha do tempo'],
        ['dashboard/gallery',      'Galeria'],
        ['dashboard/coupons',      'Cupons'],
        ['dashboard/contact',      'Contato'],
    ],
    'Aparência & mais' => [
        ['dashboard/themes',    'Temas'],
        ['dashboard/qr',        'QR Code'],
        ['dashboard/analytics', 'Analytics'],
        ['dashboard/profile',   'Perfil'],
    ],
];
function nav_active(string $path, string $current): bool {
    $u = url($path);
    return rtrim($current, '/') === rtrim($u, '/');
}
?>

<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="hidden lg:flex w-64 shrink-0 flex-col border-r border-white/5 px-5 py-6">
        <div class="px-2"><?php require BASE_DIR . '/app/Views/partials/brand.php'; ?></div>

        <nav class="mt-8 space-y-5 overflow-y-auto">
            <?php foreach ($navGroups as $group => $items): ?>
                <div>
                    <p class="px-3 mb-1.5 text-[10px] uppercase tracking-[0.14em] text-zinc-600 font-semibold"><?= e($group) ?></p>
                    <?php foreach ($items as [$path, $label]):
                        $active = nav_active($path, $current); ?>
                        <a href="<?= url($path) ?>"
                           class="block rounded-xl px-3 py-2 text-sm transition
                                  <?= $active ? 'bg-white/10 text-white' : 'text-zinc-400 hover:text-white hover:bg-white/5' ?>">
                            <?= e($label) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </nav>

        <div class="mt-auto pt-6 border-t border-white/5">
            <a href="<?= url('u/' . $me['username']) ?>" target="_blank"
               class="block text-sm text-sky-300 hover:text-sky-200 px-3 py-2">↗ Ver minha página</a>
            <form method="post" action="<?= url('logout') ?>">
                <?= csrf_field() ?>
                <button class="w-full text-left text-sm text-zinc-400 hover:text-white px-3 py-2">Sair</button>
            </form>
        </div>
    </aside>

    <!-- Conteúdo -->
    <div class="flex-1 min-w-0">
        <!-- Topbar (mobile + título) -->
        <header class="sticky top-0 z-20 border-b border-white/5 bg-ink/80 backdrop-blur">
            <div class="flex items-center justify-between px-5 lg:px-8 h-16">
                <div class="flex items-center gap-3">
                    <button data-menu-toggle class="lg:hidden p-2 -ml-2 text-zinc-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-lg font-semibold text-white"><?= e($title ?? 'Dashboard') ?></h1>
                </div>
                <a href="<?= url('u/' . $me['username']) ?>" target="_blank"
                   class="hidden sm:inline-flex text-sm px-4 py-2 rounded-full glass hover:bg-white/10 transition">
                    originium.app/u/<?= e($me['username']) ?>
                </a>
            </div>
            <!-- Menu mobile -->
            <div data-menu class="hidden lg:hidden border-t border-white/5 px-3 py-3 space-y-1 max-h-[70vh] overflow-y-auto">
                <?php foreach ($navGroups as $group => $items): ?>
                    <p class="px-3 pt-2 text-[10px] uppercase tracking-[0.14em] text-zinc-600 font-semibold"><?= e($group) ?></p>
                    <?php foreach ($items as [$path, $label]): ?>
                        <a href="<?= url($path) ?>" class="block rounded-xl px-3 py-2 text-sm text-zinc-300 hover:bg-white/5"><?= e($label) ?></a>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <a href="<?= url('u/' . $me['username']) ?>" target="_blank" class="block rounded-xl px-3 py-2.5 text-sm text-sky-300">↗ Ver minha página</a>
                <form method="post" action="<?= url('logout') ?>" class="px-3 pt-1"><?= csrf_field() ?><button class="text-sm text-zinc-400 hover:text-white">Sair</button></form>
            </div>
        </header>

        <main class="px-5 lg:px-8 py-8 max-w-5xl">
            <?php require BASE_DIR . '/app/Views/partials/flash.php'; ?>
            <?= $content ?>
        </main>
    </div>
</div>

<?php require BASE_DIR . '/app/Views/partials/foot.php'; ?>
