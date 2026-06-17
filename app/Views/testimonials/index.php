<?php $isEdit = $editing !== null; $val = fn($k, $d = '') => e(old($k, $editing[$k] ?? $d)); ?>

<p class="text-sm text-zinc-400 mb-6">Recomendações exibidas como balões de citação no seu perfil.</p>

<div class="grid lg:grid-cols-5 gap-6">
    <form method="post" action="<?= url($isEdit ? $route . '/' . $editing['id'] : $route) ?>" class="lg:col-span-2 glass-strong rounded-2xl p-5 space-y-3 h-fit">
        <?= csrf_field() ?>
        <h2 class="font-semibold text-white"><?= $isEdit ? 'Editar depoimento' : 'Novo depoimento' ?></h2>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Nome do autor</label>
            <input name="author_name" value="<?= $val('author_name') ?>" class="field" placeholder="João Pereira">
            <?php if ($m = error_for('author_name')): ?><p class="text-xs text-rose-400 mt-1"><?= e($m) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Cargo / contexto</label>
            <input name="author_role" value="<?= $val('author_role') ?>" class="field" placeholder="CEO, Empresa X">
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Depoimento</label>
            <textarea name="quote" rows="4" class="field resize-none" placeholder="Trabalho impecável..."><?= $val('quote') ?></textarea>
            <?php if ($m = error_for('quote')): ?><p class="text-xs text-rose-400 mt-1"><?= e($m) ?></p><?php endif; ?>
        </div>
        <label class="flex items-center gap-2 text-sm text-zinc-300">
            <input type="checkbox" name="is_active" value="1" <?= ($isEdit ? (int) $editing['is_active'] === 1 : true) ? 'checked' : '' ?> class="accent-orange-500">
            Visível no perfil
        </label>
        <div class="flex items-center gap-3 pt-1">
            <button class="px-5 py-2 rounded-full bg-orange-600 text-white text-sm font-medium hover:bg-orange-500 transition"><?= $isEdit ? 'Salvar' : 'Adicionar' ?></button>
            <?php if ($isEdit): ?><a href="<?= url($route) ?>" class="text-sm text-zinc-400 hover:text-white">Cancelar</a><?php endif; ?>
        </div>
    </form>

    <div class="lg:col-span-3 space-y-2">
        <?php if (empty($items)): ?>
            <div class="glass rounded-2xl p-8 text-center text-sm text-zinc-500">Nenhum depoimento ainda.</div>
        <?php else: foreach ($items as $i => $item): ?>
            <div class="glass rounded-2xl px-4 py-3 flex items-start gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-zinc-200 line-clamp-2">"<?= e($item['quote']) ?>"</p>
                    <p class="text-xs text-zinc-500 mt-1"><?= e($item['author_name']) ?><?= $item['author_role'] ? ' · ' . e($item['author_role']) : '' ?>
                        <?php if ((int) $item['is_active'] !== 1): ?><span class="ml-1 text-[10px] uppercase px-1.5 py-0.5 rounded bg-zinc-700/50">oculto</span><?php endif; ?>
                    </p>
                </div>
                <?php
                $id = (int) $item['id']; $first = $i === 0; $last = $i === count($items) - 1;
                $toggle = true; $active = (int) $item['is_active'] === 1; $editUrl = url($route . '?edit=' . $id);
                require BASE_DIR . '/app/Views/partials/row_actions.php';
                ?>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>
