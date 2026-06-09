<!doctype html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <title><?= e($title ?? config('app.name')) ?></title>
    <meta name="description" content="<?= e($metaDescription ?? 'Originium — todos os seus links em um só lugar. Minimalista, rápido e seguro.') ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'] },
                    colors: { ink: { DEFAULT: '#08080a', 50: '#0d0d11', 100: '#15151b' } },
                },
            },
        };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body class="min-h-screen font-sans antialiased selection:bg-sky-500/30 <?= $bodyClass ?? 'bg-ink text-zinc-200' ?>">
