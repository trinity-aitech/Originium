<div class="text-center mb-6">
    <h1 class="text-2xl font-bold text-white">Criar conta</h1>
    <p class="text-sm text-zinc-400 mt-1">Sua página pública em segundos.</p>
</div>

<form method="post" action="<?= url('register') ?>" class="space-y-4">
    <?= csrf_field() ?>

    <div>
        <label class="block text-sm text-zinc-300 mb-1.5">Nome de exibição</label>
        <input type="text" name="display_name" value="<?= e(old('display_name')) ?>" autocomplete="name"
               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-white placeholder-zinc-500 focus:border-sky-400/50 focus:ring-2 focus:ring-sky-400/20 outline-none transition"
               placeholder="Maria Silva">
        <?php if ($m = error_for('display_name')): ?><p class="mt-1 text-xs text-rose-400"><?= e($m) ?></p><?php endif; ?>
    </div>

    <div>
        <label class="block text-sm text-zinc-300 mb-1.5">Usuário</label>
        <div class="flex items-center rounded-xl bg-white/5 border border-white/10 focus-within:border-sky-400/50 focus-within:ring-2 focus-within:ring-sky-400/20 transition">
            <span class="pl-4 text-sm text-zinc-500">originium.app/u/</span>
            <input type="text" name="username" value="<?= e(old('username')) ?>" autocomplete="username"
                   class="flex-1 bg-transparent px-1 py-2.5 text-white placeholder-zinc-500 outline-none"
                   placeholder="maria">
        </div>
        <?php if ($m = error_for('username')): ?><p class="mt-1 text-xs text-rose-400"><?= e($m) ?></p><?php endif; ?>
    </div>

    <div>
        <label class="block text-sm text-zinc-300 mb-1.5">E-mail</label>
        <input type="email" name="email" value="<?= e(old('email')) ?>" autocomplete="email"
               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-white placeholder-zinc-500 focus:border-sky-400/50 focus:ring-2 focus:ring-sky-400/20 outline-none transition"
               placeholder="voce@email.com">
        <?php if ($m = error_for('email')): ?><p class="mt-1 text-xs text-rose-400"><?= e($m) ?></p><?php endif; ?>
    </div>

    <div>
        <label class="block text-sm text-zinc-300 mb-1.5">Senha</label>
        <input type="password" name="password" autocomplete="new-password"
               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-white placeholder-zinc-500 focus:border-sky-400/50 focus:ring-2 focus:ring-sky-400/20 outline-none transition"
               placeholder="Mínimo 8 caracteres">
        <?php if ($m = error_for('password')): ?><p class="mt-1 text-xs text-rose-400"><?= e($m) ?></p><?php endif; ?>
    </div>

    <div>
        <label class="block text-sm text-zinc-300 mb-1.5">Confirmar senha</label>
        <input type="password" name="password_confirmation" autocomplete="new-password"
               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-white placeholder-zinc-500 focus:border-sky-400/50 focus:ring-2 focus:ring-sky-400/20 outline-none transition"
               placeholder="Repita a senha">
    </div>

    <button class="w-full rounded-xl bg-white text-ink font-medium py-2.5 hover:bg-zinc-200 transition">
        Criar conta
    </button>
</form>

<p class="mt-6 text-center text-sm text-zinc-400">
    Já tem conta? <a href="<?= url('login') ?>" class="text-sky-300 hover:text-sky-200">Entrar</a>
</p>
