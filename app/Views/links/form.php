<?php
$isEdit = $link !== null;
$action = $isEdit ? url('dashboard/links/' . $link['id']) : url('dashboard/links');
$val = static fn (string $k, $d = '') => e(old($k, $link[$k] ?? $d));
$active = $isEdit ? (int) $link['is_active'] === 1 : true;
?>

<div class="max-w-xl">
    <a href="<?= url('dashboard/links') ?>" class="text-sm text-zinc-400 hover:text-white">← Voltar para links</a>

    <form method="post" action="<?= $action ?>" class="glass-strong rounded-3xl p-6 mt-4 space-y-4">
        <?= csrf_field() ?>

        <div>
            <label class="block text-sm text-zinc-300 mb-1.5">Título</label>
            <input type="text" name="title" value="<?= $val('title') ?>"
                   class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-white placeholder-zinc-500 focus:border-sky-400/50 focus:ring-2 focus:ring-sky-400/20 outline-none transition"
                   placeholder="Meu Instagram">
            <?php if ($m = error_for('title')): ?><p class="mt-1 text-xs text-rose-400"><?= e($m) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm text-zinc-300 mb-1.5">URL</label>
            <input type="url" name="url" value="<?= $val('url') ?>"
                   class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-white placeholder-zinc-500 focus:border-sky-400/50 focus:ring-2 focus:ring-sky-400/20 outline-none transition"
                   placeholder="https://instagram.com/voce">
            <?php if ($m = error_for('url')): ?><p class="mt-1 text-xs text-rose-400"><?= e($m) ?></p><?php endif; ?>
            <p class="mt-1.5 text-xs text-zinc-500">O ícone da rede (Instagram, LinkedIn, WhatsApp…) aparece automaticamente no perfil, conforme o link.</p>
        </div>

        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" <?= $active ? 'checked' : '' ?>
                   class="w-4 h-4 rounded accent-sky-500">
            <span class="text-sm text-zinc-300">Visível no perfil público</span>
        </label>

        <div class="pt-2">
            <button class="w-full rounded-xl bg-white text-ink font-medium py-2.5 hover:bg-zinc-200 transition">
                <?= $isEdit ? 'Salvar alterações' : 'Adicionar link' ?>
            </button>
        </div>
    </form>
</div>
