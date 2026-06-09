<div class="text-center mb-6">
    <h1 class="font-display text-3xl font-semibold text-zinc-900 dark:text-white">Criar conta</h1>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Sua página pública em segundos.</p>
</div>

<form method="post" action="<?= url('register') ?>" class="space-y-4">
    <?= csrf_field() ?>

    <div>
        <label class="block text-sm text-zinc-700 dark:text-zinc-300 mb-1.5">Nome de exibição</label>
        <input type="text" name="display_name" value="<?= e(old('display_name')) ?>" autocomplete="name" class="field" placeholder="Maria Silva">
        <?php if ($m = error_for('display_name')): ?><p class="mt-1 text-xs text-rose-500"><?= e($m) ?></p><?php endif; ?>
    </div>

    <div>
        <label class="block text-sm text-zinc-700 dark:text-zinc-300 mb-1.5">Usuário</label>
        <div class="flex items-center field !p-0 overflow-hidden">
            <span class="pl-4 text-sm text-zinc-400">originium.app/u/</span>
            <input type="text" name="username" value="<?= e(old('username')) ?>" autocomplete="username"
                   class="flex-1 bg-transparent border-0 px-1 py-2.5 outline-none text-zinc-900 dark:text-white" placeholder="maria">
        </div>
        <?php if ($m = error_for('username')): ?><p class="mt-1 text-xs text-rose-500"><?= e($m) ?></p><?php endif; ?>
    </div>

    <div>
        <label class="block text-sm text-zinc-700 dark:text-zinc-300 mb-1.5">E-mail</label>
        <input type="email" name="email" value="<?= e(old('email')) ?>" autocomplete="email" class="field" placeholder="voce@email.com">
        <?php if ($m = error_for('email')): ?><p class="mt-1 text-xs text-rose-500"><?= e($m) ?></p><?php endif; ?>
    </div>

    <div>
        <label class="block text-sm text-zinc-700 dark:text-zinc-300 mb-1.5">Senha</label>
        <input type="password" name="password" autocomplete="new-password" class="field" placeholder="Mínimo 8 caracteres">
        <?php if ($m = error_for('password')): ?><p class="mt-1 text-xs text-rose-500"><?= e($m) ?></p><?php endif; ?>
    </div>

    <div>
        <label class="block text-sm text-zinc-700 dark:text-zinc-300 mb-1.5">Confirmar senha</label>
        <input type="password" name="password_confirmation" autocomplete="new-password" class="field" placeholder="Repita a senha">
    </div>

    <button class="w-full rounded-xl bg-orange-600 text-white font-medium py-2.5 hover:bg-orange-500 transition">
        Criar conta
    </button>
</form>

<p class="mt-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
    Já tem conta? <a href="<?= url('login') ?>" class="text-orange-600 hover:text-orange-500 font-medium">Entrar</a>
</p>
