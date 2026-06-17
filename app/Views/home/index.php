<?php
$features = [
    ['Acessível',          'Construído com boas práticas e contraste cuidadoso.'],
    ['Responsivo',         'Perfeito em qualquer tela, do celular ao desktop.'],
    ['6 temas',            'Paletas minimalistas prontas, troque em um clique.'],
    ['Fácil de editar',    'Adicione, reordene e ative links sem esforço.'],
    ['Rápido',             'PHP puro, sem peso de frameworks. Carrega num piscar.'],
    ['Seguro',             'Senhas com hash, CSRF e proteção contra injeção.'],
    ['Analytics',          'Veja cliques por link e visitas ao seu perfil.'],
    ['Pronto p/ hospedar', 'Funciona em hospedagem compartilhada comum.'],
];

$faqs = [
    ['O Originium é gratuito?', 'Sim. Este é um projeto open-source que você mesmo hospeda — sem mensalidade.'],
    ['Preciso de Node.js ou banco externo?', 'Não. Roda com PHP 8+ e MySQL, exatamente o que a maioria das hospedagens compartilhadas já oferece.'],
    ['Meus dados ficam comigo?', 'Totalmente. Você hospeda o sistema, então o banco de dados e os arquivos são seus.'],
    ['Consigo personalizar o visual?', 'Sim. Há 6 temas e o código é limpo e fácil de ajustar com Tailwind.'],
];
?>

<!-- HERO com vídeo de fundo (contido, com bordas, em loop mudo) -->
<section class="px-3 sm:px-5 pt-4">
    <div class="hero-stage relative overflow-hidden rounded-[2rem] sm:rounded-[2.5rem] border border-white/10 mx-auto max-w-7xl">
        <!-- Vídeo de fundo: mudo, em loop, sem controles -->
        <video class="hero-video" autoplay muted loop playsinline preload="auto" disablepictureinpicture aria-hidden="true">
            <source src="<?= asset('videos/hero.mp4') ?>" type="video/mp4">
        </video>
        <div class="hero-overlay" aria-hidden="true"></div>

        <!-- Conteúdo do hero (sempre claro sobre o vídeo) -->
        <div class="relative z-10 px-6 pt-16 sm:pt-24 pb-12 text-center">
            <h1 class="rise font-display font-semibold tracking-tight text-white leading-[0.95]
                       text-6xl sm:text-7xl md:text-8xl lg:text-[8rem]"
                style="text-shadow:0 2px 40px rgba(0,0,0,.4)">
                Reúna tudo.
            </h1>

            <p class="rise-2 mt-7 text-lg sm:text-xl text-zinc-200 max-w-xl mx-auto leading-relaxed">
                Uma página minimalista para todos os seus links.
                Simples de criar, elegante de compartilhar.
            </p>

            <div class="rise-3 mt-9 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?= url('register') ?>" class="w-full sm:w-auto px-7 py-3.5 rounded-full bg-orange-600 text-white font-medium hover:bg-orange-500 transition shadow-lg shadow-orange-900/30">
                    Criar minha página
                </a>
                <a href="<?= url('/#recursos') ?>" class="w-full sm:w-auto px-7 py-3.5 rounded-full border border-white/25 text-white font-medium hover:bg-white/10 transition backdrop-blur-sm">
                    Ver recursos ↗
                </a>
            </div>

            <!-- Mockup (dark, consistente sobre o vídeo) -->
            <div class="mx-auto max-w-sm mt-16">
                <div class="rounded-[1.75rem] bg-white/[0.06] backdrop-blur-md border border-white/12 shadow-2xl px-6 pt-5 pb-7">
                    <div class="flex items-center gap-1.5 pb-5">
                        <span class="w-2.5 h-2.5 rounded-full bg-white/20"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-white/20"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-white/20"></span>
                        <span class="ml-2 text-[10px] text-zinc-400">originium.app/u/maria</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full mx-auto grid place-items-center bg-gradient-to-br from-amber-400 to-orange-600 text-white text-xl font-bold">M</div>
                        <p class="mt-3 font-display text-lg font-semibold text-white">Maria Silva</p>
                        <p class="text-xs text-zinc-400">Designer &amp; criadora de conteúdo</p>
                    </div>
                    <div class="mt-5 space-y-2.5">
                        <div class="rounded-xl bg-white/5 border border-white/10 py-2.5 text-center text-sm text-zinc-200">📷 Instagram</div>
                        <div class="rounded-xl bg-white/5 border border-white/10 py-2.5 text-center text-sm text-zinc-200">🎬 YouTube</div>
                        <div class="rounded-xl bg-orange-600 py-2.5 text-center text-sm text-white">✨ Meu portfólio</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Confiado por -->
<div class="mx-auto max-w-5xl px-6 mt-14 text-center">
    <p class="text-xs uppercase tracking-[0.2em] text-zinc-400">Feito para criadores, marcas e profissionais</p>
    <div class="mt-5 flex flex-wrap items-center justify-center gap-x-10 gap-y-3 text-zinc-400 dark:text-zinc-600 font-display text-xl font-medium">
        <span>Atelier</span><span>Nova</span><span>Lumen</span><span>Vértice</span><span>Studio K</span>
    </div>
</div>

<!-- RECURSOS -->
<section id="recursos" class="mx-auto max-w-6xl px-6 pt-28">
    <div class="max-w-2xl">
        <p class="text-sm font-medium text-orange-600">Recursos</p>
        <h2 class="mt-3 font-display text-4xl sm:text-5xl font-semibold tracking-tight text-zinc-900 dark:text-white leading-[1.05]">
            Tudo que você precisa. Nada que você não precise.
        </h2>
    </div>

    <div class="mt-14 grid sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-10">
        <?php foreach ($features as [$titulo, $desc]): ?>
            <div class="border-t border-black/10 dark:border-white/10 pt-5">
                <h3 class="font-semibold text-zinc-900 dark:text-white"><?= e($titulo) ?></h3>
                <p class="mt-2 text-sm text-zinc-500 leading-relaxed"><?= e($desc) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- TEMAS -->
<section id="temas" class="mx-auto max-w-6xl px-6 pt-28">
    <div class="max-w-2xl">
        <p class="text-sm font-medium text-orange-600">Temas</p>
        <h2 class="mt-3 font-display text-4xl sm:text-5xl font-semibold tracking-tight text-zinc-900 dark:text-white">
            Seis paletas. Um clique.
        </h2>
    </div>
    <div class="mt-12 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <?php
        $previews = [
            ['Glacier',  'bg-gradient-to-b from-slate-700 to-slate-900'],
            ['Midnight', 'bg-gradient-to-b from-indigo-900 to-black'],
            ['Aurora',   'bg-gradient-to-b from-teal-800 to-slate-900'],
            ['Graphite', 'bg-neutral-900'],
            ['Frost',    'bg-gradient-to-b from-sky-100 to-slate-200'],
            ['Arctic',   'bg-gradient-to-b from-zinc-100 to-white'],
        ];
        foreach ($previews as [$nome, $bg]): ?>
            <div>
                <div class="h-28 rounded-2xl <?= $bg ?> border border-black/5 dark:border-white/10"></div>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400 text-center"><?= e($nome) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- FAQ -->
<section id="faq" class="mx-auto max-w-3xl px-6 pt-28">
    <h2 class="text-center font-display text-4xl sm:text-5xl font-semibold tracking-tight text-zinc-900 dark:text-white mb-12">
        Perguntas frequentes
    </h2>
    <div class="space-y-3">
        <?php foreach ($faqs as [$q, $a]): ?>
            <details class="glass rounded-2xl px-5 py-1">
                <summary class="flex items-center justify-between py-4 text-zinc-900 dark:text-white font-medium">
                    <?= e($q) ?>
                    <span class="faq-icon text-xl text-zinc-400 leading-none">+</span>
                </summary>
                <p class="pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed"><?= e($a) ?></p>
            </details>
        <?php endforeach; ?>
    </div>
</section>

<!-- CTA FINAL -->
<section class="mx-auto max-w-5xl px-6 pt-28">
    <div class="rounded-[2.5rem] bg-zinc-900 dark:bg-white/5 dark:border dark:border-white/10 px-8 py-20 text-center">
        <h2 class="font-display text-5xl sm:text-6xl font-semibold tracking-tight text-white">Comece agora</h2>
        <p class="mt-4 text-zinc-400">Leva menos de um minuto para criar a sua.</p>
        <a href="<?= url('register') ?>" class="inline-block mt-8 px-8 py-3.5 rounded-full bg-orange-600 text-white font-medium hover:bg-orange-500 transition">
            Criar conta grátis
        </a>
    </div>
</section>
