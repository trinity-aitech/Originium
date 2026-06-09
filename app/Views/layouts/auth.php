<?php $bodyClass = 'bg-ink text-zinc-200'; require BASE_DIR . '/app/Views/partials/head.php'; ?>

<div class="relative min-h-screen flex flex-col">
    <div class="eclipse-wrap">
        <div class="eclipse-glow"></div>
    </div>

    <header class="relative z-20">
        <div class="mx-auto max-w-6xl px-6 h-16 flex items-center">
            <?php require BASE_DIR . '/app/Views/partials/brand.php'; ?>
        </div>
    </header>

    <main class="relative z-10 flex-1 grid place-items-center px-6 py-10">
        <div class="w-full max-w-md">
            <?php require BASE_DIR . '/app/Views/partials/flash.php'; ?>
            <div class="glass-strong rounded-3xl p-8 rise">
                <?= $content ?>
            </div>
        </div>
    </main>
</div>

<?php require BASE_DIR . '/app/Views/partials/foot.php'; ?>
