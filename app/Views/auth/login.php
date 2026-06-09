<div class="text-center mb-6">
    <h1 class="text-2xl font-bold text-white">Entrar</h1>
    <p class="text-sm text-zinc-400 mt-1">Bem-vindo de volta ao Originium.</p>
</div>

<form method="post" action="<?= url('login') ?>" class="space-y-4">
    <?= csrf_field() ?>

    <div>
        <label class="block text-sm text-zinc-300 mb-1.5">E-mail</label>
        <input type="email" name="email" value="<?= e(old('email')) ?>" autocomplete="email"
               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-white placeholder-zinc-500 focus:border-sky-400/50 focus:ring-2 focus:ring-sky-400/20 outline-none transition"
               placeholder="voce@email.com">
        <?php if ($m = error_for('email')): ?><p class="mt-1 text-xs text-rose-400"><?= e($m) ?></p><?php endif; ?>
    </div>

    <div>
        <label class="block text-sm text-zinc-300 mb-1.5">Senha</label>
        <input type="password" name="password" autocomplete="current-password"
               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-white placeholder-zinc-500 focus:border-sky-400/50 focus:ring-2 focus:ring-sky-400/20 outline-none transition"
               placeholder="Sua senha">
        <?php if ($m = error_for('password')): ?><p class="mt-1 text-xs text-rose-400"><?= e($m) ?></p><?php endif; ?>
    </div>

    <button class="w-full rounded-xl bg-white text-ink font-medium py-2.5 hover:bg-zinc-200 transition">
        Entrar
    </button>
</form>

<p class="mt-6 text-center text-sm text-zinc-400">
    Não tem conta? <a href="<?= url('register') ?>" class="text-sky-300 hover:text-sky-200">Criar agora</a>
</p>
