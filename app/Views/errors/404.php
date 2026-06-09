<!doctype html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Página não encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body class="min-h-screen bg-ink text-zinc-200 grid place-items-center font-sans px-6">
    <div class="text-center">
        <p class="text-7xl font-bold text-white">404</p>
        <p class="mt-3 text-zinc-400">Não encontramos essa página.</p>
        <a href="<?= url('/') ?>" class="inline-block mt-6 px-5 py-2.5 rounded-full bg-white text-ink text-sm font-medium hover:bg-zinc-200 transition">Voltar ao início</a>
    </div>
</body>
</html>
