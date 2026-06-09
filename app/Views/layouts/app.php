<?php
$bodyClass = 'bg-ink text-zinc-200';
require BASE_DIR . '/app/Views/partials/head.php';

$me = \App\Core\Auth::user();
$current = $_SERVER['REQUEST_URI'] ?? '';
$nav = [
    ['dashboard',           'Visão geral', 'M3 12l9-9 9 9M5 10v10h14V10'],
    ['dashboard/links',     'Meus links',  'M13.5 6H18a4 4 0 010 8h-4.5M10.5 18H6a4 4 0 010-8h4.5M8 12h8'],
    ['dashboard/themes',    'Temas',       'M12 3a9 9 0 100 18 3 3 0 003-3 2 2 0 012-2h1a3 3 0 003-3 9 9 0 00-12-7z'],
    ['dashboard/analytics', 'Analytics',   'M4 19V5M4 19h16M8 16v-5M12 16V8M16 16v-3'],
    ['dashboard/profile',   'Perfil',      'M12 12a4 4 0 100-8 4 4 0 000 8zM4 21a8 8 0 0116 0'],
];
function nav_active(string $path, string $current): bool {
    $u = url($path);
    if (str_contains($current, $u . '/') || rtrim($current, '/') === rtrim($u, '/')) {
        return true;
    }
    return false;
}
?>

<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="hidden lg:flex w-64 shrink-0 flex-col border-r border-white/5 px-5 py-6">
        <div class="px-2"><?php require BASE_DIR . '/app/Views/partials/brand.php'; ?></div>

        <nav class="mt-8 space-y-1">
            <?php foreach ($nav as [$path, $label, $icon]):
                $active = nav_active($path, $current); ?>
                <a href="<?= url($path) ?>"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition
                          <?= $active ? 'bg-white/10 text-white' : 'text-zinc-400 hover:text-white hover:bg-white/5' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $icon ?>"/></svg>
                    <?= e($label) ?>
                </a>
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
            <div data-menu class="hidden lg:hidden border-t border-white/5 px-3 py-3 space-y-1">
                <?php foreach ($nav as [$path, $label, $icon]): ?>
                    <a href="<?= url($path) ?>" class="block rounded-xl px-3 py-2.5 text-sm text-zinc-300 hover:bg-white/5"><?= e($label) ?></a>
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
