<?php
$cards = [
    ['Links',     $stats['links'],  'Total criados'],
    ['Ativos',    $stats['active'], 'Visíveis no perfil'],
    ['Cliques',   $stats['clicks'], 'Em todos os links'],
    ['Visitas',   $stats['views'],  'No seu perfil'],
];
$publicUrl = 'originium.app/u/' . $user['username'];
?>

<!-- Cartão de compartilhamento -->
<div class="glass-strong rounded-3xl p-6 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <p class="text-sm text-zinc-400">Sua página pública</p>
        <p class="text-lg font-semibold text-white mt-0.5"><?= e($publicUrl) ?></p>
    </div>
    <div class="flex gap-2">
        <button data-copy="<?= e(url('u/' . $user['username'])) ?>" data-label="Copiar link"
                class="px-4 py-2 rounded-full glass text-sm text-white hover:bg-white/10 transition">Copiar link</button>
        <a href="<?= url('u/' . $user['username']) ?>" target="_blank"
           class="px-4 py-2 rounded-full bg-white text-ink text-sm font-medium hover:bg-zinc-200 transition">Abrir</a>
    </div>
</div>

<!-- Estatísticas -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <?php foreach ($cards as [$label, $value, $hint]): ?>
        <div class="glass rounded-2xl p-5">
            <p class="text-3xl font-bold text-white"><?= (int) $value ?></p>
            <p class="text-sm text-zinc-300 mt-1"><?= e($label) ?></p>
            <p class="text-xs text-zinc-500"><?= e($hint) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<!-- Checklist de conclusão do perfil -->
<?php if ($checklist['done'] < $checklist['total']): ?>
    <div class="glass rounded-3xl p-6 mb-8">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-white">Complete seu perfil</h2>
            <span class="text-sm text-zinc-400"><?= $checklist['done'] ?>/<?= $checklist['total'] ?></span>
        </div>
        <div class="h-2 rounded-full bg-white/10 overflow-hidden mb-5">
            <div class="h-full rounded-full bg-orange-500 transition-all duration-500" style="width: <?= $checklist['percent'] ?>%"></div>
        </div>
        <ul class="space-y-1.5">
            <?php foreach ($checklist['items'] as [$label, $done, $link]): ?>
                <li>
                    <a href="<?= url($link) ?>" class="flex items-center gap-3 rounded-lg px-2 py-1.5 hover:bg-white/5 transition">
                        <span class="w-5 h-5 rounded-full grid place-items-center text-xs shrink-0 <?= $done ? 'bg-emerald-500/20 text-emerald-400' : 'border border-white/15 text-transparent' ?>">✓</span>
                        <span class="text-sm <?= $done ? 'text-zinc-500 line-through' : 'text-zinc-200' ?>"><?= e($label) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($unread)): ?>
    <a href="<?= url('dashboard/contact') ?>" class="block glass rounded-2xl px-5 py-4 mb-8 hover:bg-white/5 transition">
        <span class="text-sm text-white font-medium">✉ <?= (int) $unread ?> nova(s) mensagem(ns)</span>
        <span class="text-sm text-zinc-500"> — no formulário de contato</span>
    </a>
<?php endif; ?>

<!-- Ações rápidas / links recentes -->
<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-white">Seus links</h2>
    <a href="<?= url('dashboard/links/create') ?>" class="px-4 py-2 rounded-full bg-white text-ink text-sm font-medium hover:bg-zinc-200 transition">+ Adicionar</a>
</div>

<?php if (empty($links)): ?>
    <div class="glass rounded-2xl p-10 text-center">
        <p class="text-zinc-300 font-medium">Você ainda não tem links.</p>
        <p class="text-sm text-zinc-500 mt-1">Adicione o primeiro para começar a montar sua página.</p>
        <a href="<?= url('dashboard/links/create') ?>" class="inline-block mt-5 px-5 py-2.5 rounded-full bg-white text-ink text-sm font-medium hover:bg-zinc-200 transition">Criar primeiro link</a>
    </div>
<?php else: ?>
    <div class="space-y-2">
        <?php foreach (array_slice($links, 0, 5) as $link): ?>
            <div class="glass rounded-2xl px-5 py-3.5 flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-white font-medium truncate"><?= e($link['title']) ?></p>
                    <p class="text-xs text-zinc-500 truncate"><?= e($link['url']) ?></p>
                </div>
                <span class="shrink-0 text-sm text-zinc-400"><?= (int) $link['clicks_count'] ?> cliques</span>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="<?= url('dashboard/links') ?>" class="inline-block mt-4 text-sm text-sky-300 hover:text-sky-200">Gerenciar todos os links →</a>
<?php endif; ?>
