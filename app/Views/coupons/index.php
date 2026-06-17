<?php $isEdit = $editing !== null; $val = fn($k, $d = '') => e(old($k, $editing[$k] ?? $d)); ?>

<p class="text-sm text-zinc-400 mb-6">Cupons de desconto exibidos no perfil — o visitante copia o código com um clique.</p>

<div class="grid lg:grid-cols-5 gap-6">
    <form method="post" action="<?= url($isEdit ? $route . '/' . $editing['id'] : $route) ?>" class="lg:col-span-2 glass-strong rounded-2xl p-5 space-y-3 h-fit">
        <?= csrf_field() ?>
        <h2 class="font-semibold text-white"><?= $isEdit ? 'Editar cupom' : 'Novo cupom' ?></h2>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Código</label>
            <input name="code" value="<?= $val('code') ?>" class="field font-mono uppercase" placeholder="BEMVINDO10">
            <?php if ($m = error_for('code')): ?><p class="text-xs text-rose-400 mt-1"><?= e($m) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Desconto (rótulo)</label>
            <input name="discount_label" value="<?= $val('discount_label') ?>" class="field" placeholder="10% OFF">
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Descrição</label>
            <input name="description" value="<?= $val('description') ?>" class="field" placeholder="Primeira compra">
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Link (opcional)</label>
            <input name="url" value="<?= $val('url') ?>" class="field" placeholder="https://loja.com">
            <?php if ($m = error_for('url')): ?><p class="text-xs text-rose-400 mt-1"><?= e($m) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Expira em (opcional)</label>
            <input type="date" name="expires_at" value="<?= $val('expires_at') ?>" class="field">
        </div>
        <label class="flex items-center gap-2 text-sm text-zinc-300">
            <input type="checkbox" name="is_active" value="1" <?= ($isEdit ? (int) $editing['is_active'] === 1 : true) ? 'checked' : '' ?> class="accent-orange-500">
            Ativo
        </label>
        <div class="flex items-center gap-3 pt-1">
            <button class="px-5 py-2 rounded-full bg-orange-600 text-white text-sm font-medium hover:bg-orange-500 transition"><?= $isEdit ? 'Salvar' : 'Adicionar' ?></button>
            <?php if ($isEdit): ?><a href="<?= url($route) ?>" class="text-sm text-zinc-400 hover:text-white">Cancelar</a><?php endif; ?>
        </div>
    </form>

    <div class="lg:col-span-3 space-y-2">
        <?php if (empty($items)): ?>
            <div class="glass rounded-2xl p-8 text-center text-sm text-zinc-500">Nenhum cupom ainda.</div>
        <?php else: foreach ($items as $i => $item): ?>
            <div class="glass rounded-2xl px-4 py-3 flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-mono font-semibold text-white"><?= e(strtoupper($item['code'])) ?>
                        <?php if ($item['discount_label']): ?><span class="ml-2 text-xs text-orange-400 font-sans"><?= e($item['discount_label']) ?></span><?php endif; ?>
                        <?php if ((int) $item['is_active'] !== 1): ?><span class="ml-1 text-[10px] uppercase px-1.5 py-0.5 rounded bg-zinc-700/50 font-sans">oculto</span><?php endif; ?>
                    </p>
                    <p class="text-xs text-zinc-500 truncate"><?= e($item['description'] ?: '') ?><?= $item['expires_at'] ? ' · expira ' . e($item['expires_at']) : '' ?></p>
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
