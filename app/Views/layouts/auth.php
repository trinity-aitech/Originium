<?php require BASE_DIR . '/app/Views/partials/head.php'; ?>

<div class="relative min-h-screen flex flex-col overflow-hidden">
    <div class="glow-soft"></div>

    <header class="relative z-20">
        <div class="mx-auto max-w-6xl px-6 h-20 flex items-center justify-between">
            <?php require BASE_DIR . '/app/Views/partials/brand.php'; ?>
            <?php require BASE_DIR . '/app/Views/partials/theme-toggle.php'; ?>
        </div>
    </header>

    <main class="relative z-10 flex-1 grid place-items-center px-6 py-10">
        <div class="w-full max-w-md">
            <?php require BASE_DIR . '/app/Views/partials/flash.php'; ?>
            <div class="glass-strong rounded-3xl p-8 rise shadow-xl shadow-black/5">
                <?= $content ?>
            </div>
        </div>
    </main>
</div>

<?php require BASE_DIR . '/app/Views/partials/foot.php'; ?>
