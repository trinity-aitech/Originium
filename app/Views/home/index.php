<?php
$features = [
    ['Acessível',          'Construído com boas práticas e contraste cuidadoso.',        'M9 12l2 2 4-4'],
    ['Responsivo',         'Perfeito em qualquer tela, do celular ao desktop.',          'M4 6h16v10H4zM2 20h20'],
    ['Temas frios',        '6 temas minimalistas prontos, troque em um clique.',          'M12 3a9 9 0 100 18 3 3 0 003-3 2 2 0 012-2h1a3 3 0 003-3 9 9 0 00-12-7z'],
    ['Fácil de editar',    'Adicione, reordene e ative links sem esforço.',               'M11 4h2M4 11v2M12 12l7-7 2 2-7 7-3 1z'],
    ['Rápido',             'PHP puro, sem peso de frameworks. Carrega num piscar.',        'M13 2L3 14h7l-1 8 10-12h-7z'],
    ['Seguro',             'Senhas com hash, CSRF e proteção contra injeção.',            'M12 3l8 4v5c0 5-3.5 7.5-8 9-4.5-1.5-8-4-8-9V7z'],
    ['Analytics',          'Veja cliques por link e visitas ao seu perfil.',              'M4 19V5M4 19h16M8 16v-5M12 16V8M16 16v-3'],
    ['Pronto p/ hospedar', 'Funciona em hospedagem compartilhada comum.',                 'M3 15a4 4 0 014-4h.5a5.5 5.5 0 0110.9-1A4.5 4.5 0 0120 19H7a4 4 0 01-4-4z'],
];

$faqs = [
    ['O Originium é gratuito?', 'Sim. Este é um projeto open-source que você mesmo hospeda — sem mensalidade.'],
    ['Preciso de Node.js ou banco externo?', 'Não. Roda com PHP 8+ e MySQL, exatamente o que a maioria das hospedagens compartilhadas já oferece.'],
    ['Meus dados ficam comigo?', 'Totalmente. Você hospeda o sistema, então o banco de dados e os arquivos são seus.'],
    ['Consigo personalizar o visual?', 'Sim. Há 6 temas em tons frios e o código é limpo e fácil de ajustar com Tailwind.'],
];
?>

<!-- HERO -->
<section class="relative overflow-hidden">
    <div class="eclipse-wrap">
        <div class="eclipse-glow"></div>
        <div class="eclipse"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-4xl px-6 pt-20 pb-40 text-center">
        <span class="rise inline-flex items-center gap-2 rounded-full glass px-3 py-1 text-xs text-zinc-300 mb-8">
            <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span> Sua bio link, com estilo premium
        </span>

        <h1 class="rise text-5xl sm:text-6xl md:text-7xl font-bold tracking-tight leading-[1.05] text-white">
            Todos os seus links.<br>
            <span class="text-zinc-500">Em um só lugar.</span>
        </h1>

        <p class="rise-2 mt-6 text-lg text-zinc-400 max-w-xl mx-auto">
            Crie uma página pública minimalista para reunir tudo o que importa.
            Rápida, segura e personalizável em segundos.
        </p>

        <div class="rise-3 mt-10 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="<?= url('register') ?>" class="w-full sm:w-auto px-6 py-3 rounded-full bg-white text-ink font-medium hover:bg-zinc-200 transition">
                Criar minha página
            </a>
            <a href="<?= url('/#recursos') ?>" class="w-full sm:w-auto px-6 py-3 rounded-full glass text-white hover:bg-white/10 transition">
                Ver recursos
            </a>
        </div>
    </div>
</section>

<!-- RECURSOS -->
<section id="recursos" class="relative mx-auto max-w-6xl px-6 py-20">
    <div class="text-center mb-16">
        <h2 class="text-4xl sm:text-5xl font-bold tracking-tight text-white">
            Tudo que você precisa.<br>
            <span class="text-zinc-500">Nada que você não precise.</span>
        </h2>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-10">
        <?php foreach ($features as [$titulo, $desc, $icon]): ?>
            <div>
                <div class="flex items-center gap-2 text-white">
                    <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $icon ?>"/></svg>
                    <h3 class="font-semibold"><?= e($titulo) ?></h3>
                </div>
                <p class="mt-2 text-sm text-zinc-500 leading-relaxed"><?= e($desc) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- FAQ -->
<section id="faq" class="mx-auto max-w-3xl px-6 py-20">
    <h2 class="text-center text-4xl font-bold tracking-tight text-white mb-12">Perguntas frequentes</h2>
    <div class="space-y-3">
        <?php foreach ($faqs as [$q, $a]): ?>
            <details class="glass rounded-2xl px-5 py-1 group">
                <summary class="flex items-center justify-between py-4 text-white font-medium">
                    <?= e($q) ?>
                    <span class="faq-icon text-xl text-zinc-400 leading-none">+</span>
                </summary>
                <p class="pb-4 text-sm text-zinc-400 leading-relaxed"><?= e($a) ?></p>
            </details>
        <?php endforeach; ?>
    </div>
</section>

<!-- CTA FINAL -->
<section class="relative overflow-hidden">
    <div class="mx-auto max-w-3xl px-6 py-24 text-center">
        <h2 class="text-5xl font-bold tracking-tight text-white">Comece agora</h2>
        <p class="mt-4 text-zinc-400">Leva menos de um minuto para criar a sua.</p>
        <div class="mt-8">
            <a href="<?= url('register') ?>" class="inline-block px-7 py-3 rounded-full bg-white text-ink font-medium hover:bg-zinc-200 transition">
                Criar conta grátis
            </a>
        </div>
    </div>
</section>
