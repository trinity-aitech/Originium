<?php
$name    = $user['display_name'] ?: $user['username'];
$initial = mb_strtoupper(mb_substr($name, 0, 1));
$p       = $palette;

// Blocos do Blueprint que aparecem como "chips" rápidos
$chips = array_filter([
    'Disponibilidade' => $user['bp_availability'] ?? '',
    'Horário'         => $user['bp_working_hours'] ?? '',
    'Status'          => $user['bp_project_status'] ?? '',
]);
// Blocos descritivos
$blocks = array_filter([
    'Valores'                  => $user['bp_values'] ?? '',
    'Como eu trabalho'         => $user['bp_work_method'] ?? '',
    'Preferências de contato'  => $user['bp_contact_prefs'] ?? '',
    'Para quem é ideal'        => $user['bp_client_compat'] ?? '',
    'O que esperar'            => $user['bp_expectations'] ?? '',
]);
?><!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($user['bio'] ?: ('Página de ' . $name)) ?>">
    <meta property="og:title" content="<?= e($name) ?>">
    <meta property="og:description" content="<?= e($user['bio'] ?: ('Página de ' . $name)) ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset_v('css/app.css') ?>">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .font-display { font-family: 'Fraunces', Georgia, serif; }
        :root {
            --pf-bg-from: <?= e($p['bg_from']) ?>;
            --pf-bg-to: <?= e($p['bg_to']) ?>;
            --pf-surface: <?= e($p['surface']) ?>;
            --pf-surface-hover: <?= e($p['surface_hover']) ?>;
            --pf-border: <?= e($p['border']) ?>;
            --pf-text: <?= e($p['text']) ?>;
            --pf-muted: <?= e($p['muted']) ?>;
            --pf-accent: <?= e($p['accent']) ?>;
            --pf-accent-contrast: <?= e($p['accent_contrast']) ?>;
        }
    </style>
</head>
<body class="pf-body antialiased">
    <?php if ($p['animation'] !== 'none' && $p['animation'] !== ''): ?>
        <div class="pf-bg pf-bg--<?= e($p['animation']) ?>"></div>
    <?php endif; ?>

    <main class="pf-content mx-auto max-w-md px-6 py-16">
        <?php $contactState = $_GET['contact'] ?? null; ?>
        <?php if ($contactState === 'sent'): ?>
            <div class="mb-6 rounded-2xl px-4 py-3 text-sm text-center pf-card">✓ Mensagem enviada. Obrigado!</div>
        <?php elseif ($contactState === 'error'): ?>
            <div class="mb-6 rounded-2xl px-4 py-3 text-sm text-center pf-card">Preencha os campos obrigatórios.</div>
        <?php elseif ($contactState === 'limit'): ?>
            <div class="mb-6 rounded-2xl px-4 py-3 text-sm text-center pf-card">Muitos envios. Tente novamente mais tarde.</div>
        <?php endif; ?>

        <!-- Cabeçalho -->
        <header class="text-center">
            <?php if (!empty($user['avatar_path'])): ?>
                <img src="<?= asset($user['avatar_path']) ?>" alt="<?= e($name) ?>"
                     class="w-24 h-24 rounded-full object-cover mx-auto shadow-lg" style="box-shadow:0 8px 30px rgba(0,0,0,.25)">
            <?php else: ?>
                <div class="w-24 h-24 rounded-full mx-auto grid place-items-center text-3xl font-bold pf-btn-accent shadow-lg">
                    <?= e($initial) ?>
                </div>
            <?php endif; ?>

            <h1 class="mt-5 font-display text-4xl font-semibold pf-text"><?= e($name) ?></h1>
            <?php if (!empty($user['headline'])): ?>
                <p class="mt-1.5 text-base font-medium pf-accent"><?= e($user['headline']) ?></p>
            <?php endif; ?>
            <?php if (!empty($user['bio'])): ?>
                <p class="mt-3 text-[15px] pf-muted leading-relaxed"><?= e($user['bio']) ?></p>
            <?php endif; ?>

            <?php if (!empty($user['bp_current_focus'])): ?>
                <div class="mt-4 inline-flex items-center gap-2 rounded-full px-3.5 py-2 pf-chip text-sm">
                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--pf-accent)"></span>
                    Foco atual: <?= e($user['bp_current_focus']) ?>
                </div>
            <?php endif; ?>

            <?php if ($chips): ?>
                <div class="mt-3 flex flex-wrap justify-center gap-2">
                    <?php foreach ($chips as $label => $value): ?>
                        <span class="rounded-full px-3.5 py-2 pf-chip text-sm"><?= e($label) ?>: <?= e($value) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </header>

        <!-- Links -->
        <section class="mt-10 space-y-3">
            <?php foreach ($links as $link): ?>
                <?php $svg = social_icon($link['url']); ?>
                <a href="<?= url('l/' . $link['id']) ?>" target="_blank" rel="noopener noreferrer"
                   class="pf-card pf-link flex items-center justify-center gap-2.5 rounded-2xl px-5 py-4 text-center font-medium text-[1.05rem]">
                    <?php if ($svg !== null): ?><?= $svg ?><?php endif; ?>
                    <span><?= e($link['title']) ?></span>
                </a>
            <?php endforeach; ?>
        </section>

        <!-- Cupons -->
        <?php if (!empty($coupons)): ?>
            <section class="mt-8 space-y-3">
                <p class="pf-section-label mb-1">Cupons</p>
                <?php foreach ($coupons as $coupon): ?>
                    <div class="pf-card rounded-2xl p-4 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <?php if (!empty($coupon['discount_label'])): ?>
                                <span class="text-base font-semibold pf-accent"><?= e($coupon['discount_label']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($coupon['description'])): ?>
                                <p class="text-sm pf-muted truncate"><?= e($coupon['description']) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php $code = strtoupper($coupon['code']); ?>
                        <button type="button" data-copy="<?= e($code) ?>" data-label="<?= e($code) ?>"
                                class="shrink-0 rounded-xl px-3 py-2 text-xs font-mono font-semibold pf-btn-accent">
                            <?= e($code) ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <!-- Blueprint descritivo -->
        <?php if ($blocks): ?>
            <section class="mt-10 space-y-3">
                <?php foreach ($blocks as $label => $value): ?>
                    <div class="pf-card rounded-2xl p-5">
                        <p class="pf-section-label mb-2.5"><?= e($label) ?></p>
                        <p class="text-[15px] pf-text leading-relaxed whitespace-pre-line"><?= e($value) ?></p>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <!-- Galeria (slideshow com navegação) -->
        <?php if (!empty($gallery)): ?>
            <section class="mt-10">
                <p class="pf-section-label mb-3">Galeria</p>
                <div class="relative" data-gallery>
                    <div class="slideshow rounded-2xl" data-gallery-track>
                        <?php foreach ($gallery as $img): ?>
                            <figure class="relative rounded-2xl overflow-hidden">
                                <img src="<?= asset($img['image_path']) ?>" alt="<?= e($img['caption'] ?? '') ?>" class="w-full h-60 object-cover">
                                <?php if (!empty($img['caption'])): ?>
                                    <figcaption class="absolute bottom-0 inset-x-0 p-3 text-xs text-white bg-gradient-to-t from-black/60 to-transparent"><?= e($img['caption']) ?></figcaption>
                                <?php endif; ?>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($gallery) > 1): ?>
                        <button type="button" data-gallery-prev aria-label="Imagem anterior" class="gal-nav gal-prev">&#8249;</button>
                        <button type="button" data-gallery-next aria-label="Próxima imagem" class="gal-nav gal-next">&#8250;</button>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Depoimentos (quote bubbles) -->
        <?php if (!empty($testimonials)): ?>
            <section class="mt-10 space-y-4">
                <p class="pf-section-label">Recomendações</p>
                <?php foreach ($testimonials as $t): ?>
                    <div>
                        <blockquote class="quote-bubble p-4 text-[15px] pf-text leading-relaxed">"<?= e($t['quote']) ?>"</blockquote>
                        <div class="mt-3 ml-2 flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-full grid place-items-center text-sm font-bold pf-btn-accent"><?= e(mb_strtoupper(mb_substr($t['author_name'], 0, 1))) ?></span>
                            <div>
                                <p class="text-sm font-semibold pf-text"><?= e($t['author_name']) ?></p>
                                <?php if (!empty($t['author_role'])): ?><p class="text-xs pf-muted"><?= e($t['author_role']) ?></p><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <!-- Linha do tempo -->
        <?php if (!empty($timeline)): ?>
            <section class="mt-10">
                <p class="pf-section-label mb-4">Trajetória</p>
                <ol class="relative border-l pf-border ml-2 space-y-6">
                    <?php foreach ($timeline as $ev): ?>
                        <li class="pl-5 relative">
                            <span class="absolute -left-[5px] top-1.5 w-2 h-2 rounded-full" style="background: var(--pf-accent)"></span>
                            <p class="text-sm font-medium pf-accent"><?= e($ev['period']) ?></p>
                            <p class="text-base font-semibold pf-text"><?= e($ev['title']) ?></p>
                            <?php if (!empty($ev['description'])): ?><p class="text-sm pf-muted mt-0.5 leading-relaxed"><?= e($ev['description']) ?></p><?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </section>
        <?php endif; ?>

        <!-- FAQ -->
        <?php if (!empty($faqs)): ?>
            <section class="mt-10 space-y-2">
                <p class="pf-section-label mb-1">Perguntas frequentes</p>
                <?php foreach ($faqs as $faq): ?>
                    <details class="pf-card rounded-2xl px-4 py-1">
                        <summary class="flex items-center justify-between py-3.5 text-base font-medium pf-text">
                            <?= e($faq['question']) ?>
                            <span class="faq-icon text-xl pf-muted leading-none">+</span>
                        </summary>
                        <p class="pb-3.5 text-[15px] pf-muted leading-relaxed whitespace-pre-line"><?= e($faq['answer']) ?></p>
                    </details>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <!-- Formulário de contato -->
        <?php if (!empty($contactFields)): ?>
            <section class="mt-10">
                <p class="pf-section-label mb-3">Fale comigo</p>
                <form method="post" action="<?= url('u/' . $user['username'] . '/contact') ?>" class="pf-card rounded-2xl p-5 space-y-3">
                    <?= csrf_field() ?>
                    <?php foreach ($contactFields as $f):
                        $fname = 'field_' . $f['id'];
                        $req = (int) $f['is_required'] === 1 ? 'required' : ''; ?>
                        <div>
                            <label class="block text-sm font-medium pf-muted mb-1.5"><?= e($f['label']) ?><?= $req ? ' *' : '' ?></label>
                            <?php if ($f['field_type'] === 'textarea'): ?>
                                <textarea name="<?= e($fname) ?>" rows="3" <?= $req ?> placeholder="<?= e($f['placeholder'] ?? '') ?>"
                                          class="w-full rounded-xl px-3.5 py-2.5 text-base outline-none pf-card resize-none"></textarea>
                            <?php else: ?>
                                <input type="<?= e($f['field_type']) ?>" name="<?= e($fname) ?>" <?= $req ?> placeholder="<?= e($f['placeholder'] ?? '') ?>"
                                       class="w-full rounded-xl px-3.5 py-2.5 text-base outline-none pf-card">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <button class="w-full rounded-xl py-3 text-base font-medium pf-btn-accent">Enviar mensagem</button>
                </form>
            </section>
        <?php endif; ?>

        <!-- QR Code do perfil -->
        <?php if ((int) ($user['show_qr'] ?? 0) === 1): ?>
            <section class="mt-10 text-center">
                <p class="pf-section-label mb-3">QR Code</p>
                <div class="inline-block rounded-2xl bg-white p-3 shadow-lg" style="box-shadow:0 8px 30px rgba(0,0,0,.18)">
                    <img src="<?= url('u/' . $user['username'] . '/qr.png') ?>" alt="QR Code do perfil"
                         width="168" height="168" class="block w-40 h-40" loading="lazy">
                </div>
                <p class="mt-3 text-sm pf-muted">Aponte a câmera para abrir este perfil.</p>
            </section>
        <?php endif; ?>

        <!-- Rodapé -->
        <footer class="mt-12 text-center">
            <a href="<?= url('/') ?>" target="_blank" rel="noopener"
               class="inline-flex items-center gap-1.5 text-xs pf-muted opacity-70 hover:opacity-100 transition">◆ Feito com Originium</a>
        </footer>
    </main>

    <script src="<?= asset_v('js/app.js') ?>"></script>
</body>
</html>
