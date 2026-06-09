<?php
$name   = $user['display_name'] ?: $user['username'];
$initial = mb_strtoupper(mb_substr($name, 0, 1));
?><!doctype html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($user['bio'] ?: ('Links de ' . $name)) ?>">
    <meta property="og:title" content="<?= e($name) ?>">
    <meta property="og:description" content="<?= e($user['bio'] ?: ('Links de ' . $name)) ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <style>body{font-family:'Inter',system-ui,sans-serif}</style>
</head>
<body class="min-h-screen antialiased <?= e($theme['bg_class']) ?>">
    <main class="mx-auto max-w-md px-6 py-16">
        <!-- Cabeçalho -->
        <div class="text-center">
            <?php if (!empty($user['avatar_path'])): ?>
                <img src="<?= asset($user['avatar_path']) ?>" alt="<?= e($name) ?>"
                     class="w-24 h-24 rounded-full object-cover mx-auto shadow-lg ring-2 ring-white/20">
            <?php else: ?>
                <div class="w-24 h-24 rounded-full mx-auto grid place-items-center bg-gradient-to-br from-sky-400 to-indigo-500 text-white text-3xl font-bold shadow-lg">
                    <?= e($initial) ?>
                </div>
            <?php endif; ?>

            <h1 class="mt-5 text-2xl font-bold <?= e($theme['text_class']) ?>"><?= e($name) ?></h1>
            <?php if (!empty($user['bio'])): ?>
                <p class="mt-2 text-sm opacity-70 <?= e($theme['text_class']) ?>"><?= e($user['bio']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Links -->
        <div class="mt-10 space-y-3">
            <?php if (empty($links)): ?>
                <p class="text-center text-sm opacity-60 <?= e($theme['text_class']) ?>">Nenhum link por aqui ainda.</p>
            <?php else: ?>
                <?php foreach ($links as $link): ?>
                    <a href="<?= url('l/' . $link['id']) ?>" rel="noopener"
                       class="flex items-center justify-center gap-2 rounded-2xl px-5 py-4 text-center font-medium transition <?= e($theme['card_class']) ?> <?= e($theme['text_class']) ?>">
                        <?php if (!empty($link['icon'])): ?><span><?= e($link['icon']) ?></span><?php endif; ?>
                        <span><?= e($link['title']) ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Rodapé -->
        <div class="mt-12 text-center">
            <a href="<?= url('/') ?>" class="inline-flex items-center gap-1.5 text-xs opacity-50 hover:opacity-80 transition <?= e($theme['text_class']) ?>">
                <span class="font-black">◆</span> Feito com Originium
            </a>
        </div>
    </main>
</body>
</html>
