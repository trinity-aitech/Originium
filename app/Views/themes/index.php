<p class="text-sm text-zinc-400 mb-6">Escolha um tema em tom frio para o seu perfil público. A mudança é aplicada na hora.</p>

<form method="post" action="<?= url('dashboard/themes') ?>">
    <?= csrf_field() ?>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($themes as $theme):
            $selected = (int) ($user['theme_id'] ?? 0) === (int) $theme['id']; ?>
            <label class="cursor-pointer block">
                <input type="radio" name="theme_id" value="<?= (int) $theme['id'] ?>" class="peer sr-only" <?= $selected ? 'checked' : '' ?>>
                <div class="rounded-2xl p-1 border-2 transition <?= $selected ? 'border-sky-400' : 'border-transparent hover:border-white/20' ?>">
                    <!-- Preview -->
                    <div class="rounded-xl overflow-hidden h-36 <?= e($theme['bg_class']) ?> grid place-items-center">
                        <div class="w-3/4 space-y-2">
                            <div class="h-7 rounded-lg <?= e($theme['card_class']) ?>"></div>
                            <div class="h-7 rounded-lg <?= e($theme['card_class']) ?>"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between px-2 py-2.5">
                        <span class="text-sm font-medium text-white"><?= e($theme['name']) ?></span>
                        <?php if ($selected): ?><span class="text-xs text-sky-300">● Ativo</span><?php endif; ?>
                    </div>
                </div>
            </label>
        <?php endforeach; ?>
    </div>

    <button class="mt-6 px-6 py-2.5 rounded-full bg-white text-ink text-sm font-medium hover:bg-zinc-200 transition">
        Aplicar tema selecionado
    </button>
</form>
