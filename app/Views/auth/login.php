<div class="text-center mb-6">
    <h1 class="font-display text-3xl font-semibold text-zinc-900 dark:text-white">Entrar</h1>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Bem-vindo de volta ao Originium.</p>
</div>

<form method="post" action="<?= url('login') ?>" class="space-y-4">
    <?= csrf_field() ?>

    <div>
        <label class="block text-sm text-zinc-700 dark:text-zinc-300 mb-1.5">E-mail</label>
        <input type="email" name="email" value="<?= e(old('email')) ?>" autocomplete="email" class="field" placeholder="voce@email.com">
        <?php if ($m = error_for('email')): ?><p class="mt-1 text-xs text-rose-500"><?= e($m) ?></p><?php endif; ?>
    </div>

    <div>
        <label class="block text-sm text-zinc-700 dark:text-zinc-300 mb-1.5">Senha</label>
        <input type="password" name="password" autocomplete="current-password" class="field" placeholder="Sua senha">
        <?php if ($m = error_for('password')): ?><p class="mt-1 text-xs text-rose-500"><?= e($m) ?></p><?php endif; ?>
    </div>

    <button class="w-full rounded-xl bg-orange-600 text-white font-medium py-2.5 hover:bg-orange-500 transition">
        Entrar
    </button>
</form>

<p class="mt-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
    Não tem conta? <a href="<?= url('register') ?>" class="text-orange-600 hover:text-orange-500 font-medium">Criar agora</a>
</p>
