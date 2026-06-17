<?php $isEdit = $editing !== null; $val = fn($k, $d = '') => e(old($k, $editing[$k] ?? $d)); ?>

<p class="text-sm text-zinc-400 mb-6">Marcos da sua trajetória, exibidos como linha do tempo no perfil.</p>

<div class="grid lg:grid-cols-5 gap-6">
    <form method="post" action="<?= url($isEdit ? $route . '/' . $editing['id'] : $route) ?>" class="lg:col-span-2 glass-strong rounded-2xl p-5 space-y-3 h-fit">
        <?= csrf_field() ?>
        <h2 class="font-semibold text-white"><?= $isEdit ? 'Editar marco' : 'Novo marco' ?></h2>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Período</label>
            <input name="period" value="<?= $val('period') ?>" class="field" placeholder="2024 · ou Jan–Mar">
            <?php if ($m = error_for('period')): ?><p class="text-xs text-rose-400 mt-1"><?= e($m) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Título</label>
            <input name="title" value="<?= $val('title') ?>" class="field" placeholder="Lancei meu estúdio">
            <?php if ($m = error_for('title')): ?><p class="text-xs text-rose-400 mt-1"><?= e($m) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Descrição (opcional)</label>
            <textarea name="description" rows="3" class="field resize-none" placeholder="Detalhe rápido sobre o marco."><?= $val('description') ?></textarea>
        </div>
        <div class="flex items-center gap-3 pt-1">
            <button class="px-5 py-2 rounded-full bg-orange-600 text-white text-sm font-medium hover:bg-orange-500 transition"><?= $isEdit ? 'Salvar' : 'Adicionar' ?></button>
            <?php if ($isEdit): ?><a href="<?= url($route) ?>" class="text-sm text-zinc-400 hover:text-white">Cancelar</a><?php endif; ?>
        </div>
    </form>

    <div class="lg:col-span-3 space-y-2">
        <?php if (empty($items)): ?>
            <div class="glass rounded-2xl p-8 text-center text-sm text-zinc-500">Nenhum marco ainda.</div>
        <?php else: foreach ($items as $i => $item): ?>
            <div class="glass rounded-2xl px-4 py-3 flex items-start gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-orange-400 font-medium"><?= e($item['period']) ?></p>
                    <p class="text-sm text-zinc-200 font-medium"><?= e($item['title']) ?></p>
                    <?php if (!empty($item['description'])): ?><p class="text-xs text-zinc-500 mt-0.5 line-clamp-2"><?= e($item['description']) ?></p><?php endif; ?>
                </div>
                <?php
                $id = (int) $item['id']; $first = $i === 0; $last = $i === count($items) - 1;
                $toggle = false; $active = false; $editUrl = url($route . '?edit=' . $id);
                require BASE_DIR . '/app/Views/partials/row_actions.php';
                ?>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>
