<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-zinc-400"><?= count($links) ?> link(s)</p>
    <a href="<?= url('dashboard/links/create') ?>" class="px-4 py-2 rounded-full bg-white text-ink text-sm font-medium hover:bg-zinc-200 transition">+ Adicionar link</a>
</div>

<?php if (empty($links)): ?>
    <div class="glass rounded-2xl p-10 text-center">
        <p class="text-zinc-300 font-medium">Nenhum link ainda.</p>
        <p class="text-sm text-zinc-500 mt-1">Crie o primeiro para aparecer na sua página pública.</p>
    </div>
<?php else: ?>
    <div class="space-y-2">
        <?php foreach ($links as $i => $link): ?>
            <div class="glass rounded-2xl px-4 py-3 flex items-center gap-3">
                <!-- Reordenar -->
                <div class="flex flex-col">
                    <form method="post" action="<?= url('dashboard/links/' . $link['id'] . '/move') ?>">
                        <?= csrf_field() ?><input type="hidden" name="direction" value="up">
                        <button class="text-zinc-500 hover:text-white disabled:opacity-20 px-1" <?= $i === 0 ? 'disabled' : '' ?>>▲</button>
                    </form>
                    <form method="post" action="<?= url('dashboard/links/' . $link['id'] . '/move') ?>">
                        <?= csrf_field() ?><input type="hidden" name="direction" value="down">
                        <button class="text-zinc-500 hover:text-white disabled:opacity-20 px-1" <?= $i === count($links) - 1 ? 'disabled' : '' ?>>▼</button>
                    </form>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <?php $svg = social_icon($link['url']); if ($svg): ?><span class="text-zinc-300 shrink-0"><?= $svg ?></span><?php endif; ?>
                        <p class="text-white font-medium truncate"><?= e($link['title']) ?></p>
                        <?php if ((int) $link['is_active'] !== 1): ?>
                            <span class="text-[10px] uppercase tracking-wide px-2 py-0.5 rounded-full bg-zinc-700/50 text-zinc-400">oculto</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-zinc-500 truncate"><?= e($link['url']) ?></p>
                </div>

                <span class="hidden sm:block text-xs text-zinc-500 shrink-0"><?= (int) $link['clicks_count'] ?> cliques</span>

                <!-- Ações -->
                <div class="flex items-center gap-1 shrink-0">
                    <form method="post" action="<?= url('dashboard/links/' . $link['id'] . '/toggle') ?>">
                        <?= csrf_field() ?>
                        <button title="Mostrar/ocultar" class="p-2 rounded-lg text-zinc-400 hover:text-white hover:bg-white/5"><?= (int) $link['is_active'] === 1 ? '👁' : '🚫' ?></button>
                    </form>
                    <a href="<?= url('dashboard/links/' . $link['id'] . '/edit') ?>" title="Editar" class="p-2 rounded-lg text-zinc-400 hover:text-white hover:bg-white/5">✎</a>
                    <form method="post" action="<?= url('dashboard/links/' . $link['id'] . '/delete') ?>" data-confirm="Remover este link?">
                        <?= csrf_field() ?>
                        <button title="Remover" class="p-2 rounded-lg text-zinc-400 hover:text-rose-400 hover:bg-white/5">🗑</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
