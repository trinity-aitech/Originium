<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? config('app.name')) ?></title>
    <meta name="description" content="<?= e($metaDescription ?? 'Originium — todos os seus links em um só lugar. Minimalista, rápido e seguro.') ?>">

    <!-- Aplica o tema antes da renderização (evita flash) -->
    <script>
        (function () {
            try {
                <?php if (!empty($forceDark)): ?>
                document.documentElement.classList.add('dark');
                <?php else: ?>
                // Claro por padrão (visual editorial). Só escurece se o usuário escolheu.
                if (localStorage.getItem('theme') === 'dark') { document.documentElement.classList.add('dark'); }
                <?php endif; ?>
            } catch (e) {}
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                        display: ['Fraunces', 'Georgia', 'serif'],
                    },
                    colors: { ink: { DEFAULT: '#08080a', 50: '#0d0d11', 100: '#15151b' }, paper: '#faf9f6' },
                },
            },
        };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body class="min-h-screen font-sans antialiased <?= $bodyClass ?? 'bg-paper text-zinc-900 dark:bg-ink dark:text-zinc-200' ?>">
