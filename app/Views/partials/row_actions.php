<?php
/* Ações de uma linha de editor. Variáveis esperadas:
   $route, $id, $first(bool), $last(bool), $toggle(bool), $active(bool), $editUrl(string|null) */
?>
<div class="flex items-center gap-1 shrink-0">
    <div class="flex flex-col">
        <form method="post" action="<?= url($route . '/' . $id . '/move') ?>">
            <?= csrf_field() ?><input type="hidden" name="direction" value="up">
            <button class="text-zinc-500 hover:text-white disabled:opacity-20 px-1 leading-none" <?= !empty($first) ? 'disabled' : '' ?>>▲</button>
        </form>
        <form method="post" action="<?= url($route . '/' . $id . '/move') ?>">
            <?= csrf_field() ?><input type="hidden" name="direction" value="down">
            <button class="text-zinc-500 hover:text-white disabled:opacity-20 px-1 leading-none" <?= !empty($last) ? 'disabled' : '' ?>>▼</button>
        </form>
    </div>
    <?php if (!empty($toggle)): ?>
        <form method="post" action="<?= url($route . '/' . $id . '/toggle') ?>">
            <?= csrf_field() ?>
            <button class="p-2 rounded-lg text-zinc-400 hover:text-white hover:bg-white/5" title="Mostrar/ocultar"><?= !empty($active) ? '👁' : '🚫' ?></button>
        </form>
    <?php endif; ?>
    <?php if (!empty($editUrl)): ?>
        <a href="<?= $editUrl ?>" class="p-2 rounded-lg text-zinc-400 hover:text-white hover:bg-white/5" title="Editar">✎</a>
    <?php endif; ?>
    <form method="post" action="<?= url($route . '/' . $id . '/delete') ?>" data-confirm="Remover este item?">
        <?= csrf_field() ?>
        <button class="p-2 rounded-lg text-zinc-400 hover:text-rose-400 hover:bg-white/5" title="Remover">🗑</button>
    </form>
</div>
