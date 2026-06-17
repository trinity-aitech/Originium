<p class="text-sm text-zinc-400 mb-6">Escolha um tema em tom frio para o seu perfil público. A mudança é aplicada na hora.</p>

<form method="post" action="<?= url('dashboard/themes') ?>">
    <?= csrf_field() ?>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($themes as $theme):
            $selected = (int) ($user['theme_id'] ?? 0) === (int) $theme['id']; ?>
            <label class="cursor-pointer block">
                <input type="radio" name="theme_id" value="<?= (int) $theme['id'] ?>" class="peer sr-only" <?= $selected ? 'checked' : '' ?>>
                <div class="rounded-2xl p-1 border-2 border-transparent transition duration-200
                            hover:border-white/20 peer-checked:border-orange-500 peer-checked:shadow-lg peer-checked:shadow-orange-900/20">
                    <!-- Preview -->
                    <div class="rounded-xl overflow-hidden h-36 <?= e($theme['bg_class']) ?> grid place-items-center">
                        <div class="w-3/4 space-y-2">
                            <div class="h-7 rounded-lg <?= e($theme['card_class']) ?>"></div>
                            <div class="h-7 rounded-lg <?= e($theme['card_class']) ?>"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between px-2 py-2.5">
                        <span class="text-sm font-medium text-white"><?= e($theme['name']) ?></span>
                        <?php if ($selected): ?><span class="text-xs text-orange-400 font-medium">● Ativo</span><?php endif; ?>
                    </div>
                </div>
            </label>
        <?php endforeach; ?>
    </div>

    <!-- Personalização -->
    <div class="mt-8 grid sm:grid-cols-2 gap-5 max-w-2xl">
        <div class="glass rounded-2xl p-5">
            <label class="block text-sm font-medium text-white mb-1">Cor de destaque</label>
            <p class="text-xs text-zinc-500 mb-3">Sobrescreve o destaque do tema (deixe em branco para usar o padrão).</p>
            <div class="flex items-center gap-3">
                <input type="color" name="accent_color" value="<?= e($user['accent_color'] ?: '#6ea8d8') ?>"
                       class="w-12 h-10 rounded-lg bg-transparent border border-white/10 cursor-pointer">
                <button type="button" data-clear-accent class="text-xs text-zinc-400 hover:text-white underline">Usar cor do tema</button>
            </div>
        </div>

        <div class="glass rounded-2xl p-5">
            <label class="block text-sm font-medium text-white mb-1">Fundo animado</label>
            <p class="text-xs text-zinc-500 mb-3">Movimento sutil no perfil público.</p>
            <select name="bg_animation" class="field">
                <?php
                $labels = ['none' => 'Nenhum', 'aurora' => 'Aurora', 'orbs' => 'Orbes', 'gradient' => 'Gradiente'];
                foreach ($animations as $anim): ?>
                    <option value="<?= e($anim) ?>" <?= ($user['bg_animation'] ?? 'none') === $anim ? 'selected' : '' ?>><?= e($labels[$anim] ?? $anim) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <button class="mt-6 px-6 py-2.5 rounded-full bg-orange-600 text-white text-sm font-medium hover:bg-orange-500 transition">
        Salvar aparência
    </button>
</form>

<script>
    document.querySelector('[data-clear-accent]')?.addEventListener('click', function () {
        // Limpa o destaque enviando vazio
        var input = document.querySelector('input[name="accent_color"]');
        input.value = '';
        input.removeAttribute('value');
        input.type = 'text';
        input.placeholder = 'Cor do tema';
        input.classList.add('field');
    });
</script>
