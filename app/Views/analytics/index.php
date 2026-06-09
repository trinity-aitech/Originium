<?php
$max = 0;
foreach ($perDay as $d) {
    $max = max($max, (int) $d['total']);
}
?>

<div class="grid grid-cols-2 gap-4 mb-8 max-w-md">
    <div class="glass rounded-2xl p-5">
        <p class="text-3xl font-bold text-white"><?= (int) $totalClicks ?></p>
        <p class="text-sm text-zinc-400 mt-1">Cliques totais</p>
    </div>
    <div class="glass rounded-2xl p-5">
        <p class="text-3xl font-bold text-white"><?= (int) $totalViews ?></p>
        <p class="text-sm text-zinc-400 mt-1">Visitas ao perfil</p>
    </div>
</div>

<!-- Gráfico de cliques por dia (CSS puro) -->
<div class="glass rounded-3xl p-6 mb-8">
    <h2 class="text-sm font-semibold text-white mb-5">Cliques nos últimos 14 dias</h2>
    <div class="flex items-end gap-1.5 h-40">
        <?php foreach ($perDay as $d):
            $h = $max > 0 ? max(4, (int) round(($d['total'] / $max) * 100)) : 4; ?>
            <div class="flex-1 group relative flex flex-col justify-end h-full">
                <div class="w-full rounded-t-md bg-gradient-to-t from-sky-500/40 to-sky-400 transition-all"
                     style="height: <?= $h ?>%"
                     title="<?= e(date('d/m', strtotime($d['date']))) ?>: <?= (int) $d['total'] ?> cliques"></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="flex justify-between mt-2 text-[10px] text-zinc-600">
        <span><?= e(date('d/m', strtotime($perDay[0]['date'] ?? 'now'))) ?></span>
        <span>hoje</span>
    </div>
</div>

<!-- Top links -->
<div class="glass rounded-3xl p-6">
    <h2 class="text-sm font-semibold text-white mb-4">Links mais clicados</h2>
    <?php if (empty($topLinks)): ?>
        <p class="text-sm text-zinc-500">Sem dados ainda. Compartilhe sua página para começar a medir.</p>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($topLinks as $i => $link): ?>
                <div class="flex items-center gap-3">
                    <span class="text-zinc-500 text-sm w-5"><?= $i + 1 ?>.</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm truncate"><?= e($link['title']) ?></p>
                        <p class="text-xs text-zinc-500 truncate"><?= e($link['url']) ?></p>
                    </div>
                    <span class="text-sm text-zinc-300 shrink-0"><?= (int) $link['clicks_count'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
