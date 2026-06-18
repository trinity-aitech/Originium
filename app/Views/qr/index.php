<p class="text-sm text-zinc-400 mb-6">QR Code da sua página pública. Imprima, compartilhe ou adicione a cartões.</p>

<div class="max-w-md">
    <div class="glass-strong rounded-3xl p-8 text-center">
        <div class="inline-block rounded-2xl bg-white p-4">
            <img src="<?= url('dashboard/qr/png') ?>" alt="QR Code do perfil" width="240" height="240" class="block w-60 h-60">
        </div>
        <p class="mt-4 text-sm text-zinc-400 break-all"><?= e($profileUrl) ?></p>
        <div class="mt-5 flex items-center justify-center gap-3">
            <a href="<?= url('dashboard/qr/png?download=1') ?>" class="px-5 py-2.5 rounded-full bg-orange-600 text-white text-sm font-medium hover:bg-orange-500 transition">Baixar PNG</a>
            <button data-copy="<?= e($profileUrl) ?>" data-label="Copiar link" class="px-5 py-2.5 rounded-full glass text-white text-sm hover:bg-white/10 transition">Copiar link</button>
        </div>
    </div>
    <p class="text-xs text-zinc-600 mt-3">Gerado 100% no servidor, em PHP puro — sem depender de internet ou serviços externos.</p>

    <!-- Exibir no perfil público -->
    <?php $on = (int) ($user['show_qr'] ?? 0) === 1; ?>
    <div class="glass rounded-2xl p-5 mt-5 flex items-center justify-between gap-4">
        <div>
            <p class="text-white font-medium">Mostrar no perfil público</p>
            <p class="text-sm text-zinc-500"><?= $on ? 'Visitantes veem o QR na sua página.' : 'Atualmente oculto para visitantes.' ?></p>
        </div>
        <form method="post" action="<?= url('dashboard/qr/toggle') ?>">
            <?= csrf_field() ?>
            <button class="px-5 py-2 rounded-full text-sm font-medium transition <?= $on ? 'glass text-white hover:bg-white/10' : 'bg-orange-600 text-white hover:bg-orange-500' ?>">
                <?= $on ? 'Ocultar' : 'Exibir no perfil' ?>
            </button>
        </form>
    </div>
</div>
