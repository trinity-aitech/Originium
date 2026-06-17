<p class="text-sm text-zinc-400 mb-6">Imagens exibidas como slideshow no seu perfil.</p>

<div class="grid lg:grid-cols-5 gap-6">
    <form method="post" action="<?= url('dashboard/gallery') ?>" enctype="multipart/form-data" class="lg:col-span-2 glass-strong rounded-2xl p-5 space-y-3 h-fit">
        <?= csrf_field() ?>
        <h2 class="font-semibold text-white">Nova imagem</h2>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Arquivo</label>
            <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                   class="text-sm text-zinc-400 file:mr-3 file:rounded-full file:border-0 file:bg-white/10 file:px-4 file:py-1.5 file:text-white hover:file:bg-white/20">
            <p class="text-xs text-zinc-600 mt-1">JPG, PNG ou WEBP. Máx 4MB.</p>
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Legenda (opcional)</label>
            <input name="caption" value="<?= e(old('caption')) ?>" class="field" placeholder="Projeto X, 2024">
        </div>
        <button class="px-5 py-2 rounded-full bg-orange-600 text-white text-sm font-medium hover:bg-orange-500 transition">Enviar</button>
    </form>

    <div class="lg:col-span-3">
        <?php if (empty($items)): ?>
            <div class="glass rounded-2xl p-8 text-center text-sm text-zinc-500">Nenhuma imagem ainda.</div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <?php foreach ($items as $i => $item): ?>
                    <div class="glass rounded-2xl overflow-hidden">
                        <img src="<?= asset($item['image_path']) ?>" alt="<?= e($item['caption'] ?? '') ?>" class="w-full h-32 object-cover">
                        <div class="p-2 flex items-center justify-between gap-1">
                            <span class="text-xs text-zinc-400 truncate"><?= e($item['caption'] ?: '—') ?></span>
                            <div class="flex items-center gap-0.5 shrink-0">
                                <form method="post" action="<?= url($route . '/' . $item['id'] . '/move') ?>"><?= csrf_field() ?><input type="hidden" name="direction" value="up"><button class="text-zinc-500 hover:text-white px-1 disabled:opacity-20" <?= $i === 0 ? 'disabled' : '' ?>>◀</button></form>
                                <form method="post" action="<?= url($route . '/' . $item['id'] . '/move') ?>"><?= csrf_field() ?><input type="hidden" name="direction" value="down"><button class="text-zinc-500 hover:text-white px-1 disabled:opacity-20" <?= $i === count($items) - 1 ? 'disabled' : '' ?>>▶</button></form>
                                <form method="post" action="<?= url($route . '/' . $item['id'] . '/delete') ?>" data-confirm="Remover imagem?"><?= csrf_field() ?><button class="text-zinc-400 hover:text-rose-400 px-1">🗑</button></form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
