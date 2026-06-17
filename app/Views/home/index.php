<?php
$features = [
    ['Sua identidade em um só lugar',
        'Compartilhe seus links, projetos, redes sociais e informações de contato em uma página feita para representar você.'],
    ['Conquiste confiança antes do primeiro contato',
        'Mostre sua forma de trabalhar, disponibilidade, experiência e tudo o que ajuda clientes e visitantes a conhecerem você melhor.'],
    ['Personalize sem limites',
        'Escolha temas, cores, galerias e elementos que deixem seu perfil com a sua identidade.'],
    ['Acompanhe seu crescimento',
        'Veja visitas, cliques e interações para entender como as pessoas descobrem e acessam seu perfil.'],
    ['Simples, rápido e acessível',
        'Uma experiência fluida em qualquer dispositivo, construída para ser fácil de usar e fácil de compartilhar.'],
];

$faqs = [
    ['O Originium é gratuito?',
        'Sim. Você pode criar seu perfil, personalizar sua página e usar todos os recursos sem mensalidades da plataforma.'],
    ['O que torna o Originium diferente?',
        'O Originium vai além de uma simples página de links. Além de reunir suas redes e projetos em um só lugar, ele ajuda você a mostrar quem é, como trabalha e o que faz de você único.'],
    ['Posso personalizar meu perfil?',
        'Sim. Escolha temas, cores e elementos visuais para criar uma página que combine com a sua identidade.'],
    ['Para quem o Originium foi criado?',
        'Para criadores, freelancers, profissionais autônomos, estudantes e qualquer pessoa que queira construir uma presença online mais pessoal e autêntica.'],
    ['Posso começar apenas com meus links?',
        'Claro. Você pode usar o Originium como uma alternativa simples ao Linktree ou aproveitar recursos extras para apresentar melhor sua história, projetos e forma de trabalhar.'],
];
?>

<!-- HERO com vídeo de fundo (retangular, amplo, em loop mudo) -->
<section class="px-2.5 sm:px-4 pt-3 sm:pt-4">
    <div class="hero-stage relative overflow-hidden rounded-[1.75rem] sm:rounded-[2.5rem] border border-white/10 mx-auto w-full max-w-[1760px] min-h-[74vh] sm:min-h-[86vh] flex items-center">
        <!-- Vídeo de fundo: mudo, em loop, sem controles -->
        <video class="hero-video" autoplay muted loop playsinline preload="auto" disablepictureinpicture aria-hidden="true">
            <source src="<?= asset('videos/hero.mp4') ?>" type="video/mp4">
        </video>
        <div class="hero-overlay" aria-hidden="true"></div>

        <!-- Conteúdo do hero (sempre claro sobre o vídeo) -->
        <div class="relative z-10 w-full px-6 py-14 sm:py-20 text-center">
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
<div class="mx-auto max-w-5xl px-6 mt-12 text-center">
    <p class="text-xs uppercase tracking-[0.2em] text-zinc-400">Feito para criadores, marcas e profissionais</p>
</div>

<!-- RECURSOS -->
<section id="recursos" class="mx-auto max-w-6xl px-6 pt-24 sm:pt-28">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="font-display text-4xl sm:text-5xl font-semibold tracking-tight text-zinc-900 dark:text-white leading-[1.08]">
            Mostre quem você é, não apenas onde te encontrar.
        </h2>
    </div>

    <div class="mt-12 sm:mt-16 grid sm:grid-cols-2 gap-x-10 gap-y-9 sm:gap-y-12 max-w-4xl mx-auto">
        <?php foreach ($features as [$titulo, $desc]): ?>
            <div class="border-t border-black/10 dark:border-white/10 pt-5">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white"><?= e($titulo) ?></h3>
                <p class="mt-2 text-[15px] text-zinc-600 dark:text-zinc-400 leading-relaxed"><?= e($desc) ?></p>
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
