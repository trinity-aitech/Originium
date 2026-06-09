<?php
$val = static fn (string $k, $d = '') => e(old($k, $user[$k] ?? $d));
?>

<div class="max-w-xl">
    <form method="post" action="<?= url('dashboard/profile') ?>" enctype="multipart/form-data" class="glass-strong rounded-3xl p-6 space-y-5">
        <?= csrf_field() ?>

        <!-- Avatar -->
        <div class="flex items-center gap-4">
            <?php if (!empty($user['avatar_path'])): ?>
                <img src="<?= asset($user['avatar_path']) ?>" alt="Avatar" class="w-16 h-16 rounded-full object-cover border border-white/10">
            <?php else: ?>
                <div class="w-16 h-16 rounded-full grid place-items-center bg-gradient-to-br from-sky-400 to-indigo-500 text-ink font-bold text-xl">
                    <?= e(mb_strtoupper(mb_substr($user['display_name'] ?: $user['username'], 0, 1))) ?>
                </div>
            <?php endif; ?>
            <div>
                <label class="block text-sm text-zinc-300 mb-1.5">Foto de perfil</label>
                <input type="file" name="avatar" accept="image/png,image/jpeg,image/webp"
                       class="text-sm text-zinc-400 file:mr-3 file:rounded-full file:border-0 file:bg-white/10 file:px-4 file:py-1.5 file:text-white hover:file:bg-white/20">
                <p class="text-xs text-zinc-600 mt-1">JPG, PNG ou WEBP. Máx 2MB.</p>
            </div>
        </div>

        <div>
            <label class="block text-sm text-zinc-300 mb-1.5">Nome de exibição</label>
            <input type="text" name="display_name" value="<?= $val('display_name') ?>"
                   class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-white focus:border-sky-400/50 focus:ring-2 focus:ring-sky-400/20 outline-none transition">
            <?php if ($m = error_for('display_name')): ?><p class="mt-1 text-xs text-rose-400"><?= e($m) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm text-zinc-300 mb-1.5">Usuário</label>
            <div class="flex items-center rounded-xl bg-white/5 border border-white/10 focus-within:border-sky-400/50 transition">
                <span class="pl-4 text-sm text-zinc-500">/u/</span>
                <input type="text" name="username" value="<?= $val('username') ?>"
                       class="flex-1 bg-transparent px-1 py-2.5 text-white outline-none">
            </div>
            <?php if ($m = error_for('username')): ?><p class="mt-1 text-xs text-rose-400"><?= e($m) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm text-zinc-300 mb-1.5">Bio <span class="text-zinc-500">(até 160 caracteres)</span></label>
            <textarea name="bio" rows="3" maxlength="160"
                      class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-white focus:border-sky-400/50 focus:ring-2 focus:ring-sky-400/20 outline-none transition resize-none"
                      placeholder="Fale um pouco sobre você"><?= $val('bio') ?></textarea>
            <?php if ($m = error_for('bio')): ?><p class="mt-1 text-xs text-rose-400"><?= e($m) ?></p><?php endif; ?>
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="<?= url('dashboard/themes') ?>" class="text-sm text-sky-300 hover:text-sky-200">Mudar tema →</a>
            <button class="px-6 py-2.5 rounded-full bg-white text-ink text-sm font-medium hover:bg-zinc-200 transition">Salvar perfil</button>
        </div>
    </form>
</div>
