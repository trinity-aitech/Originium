<?php $val = fn($k, $col = null) => e(old($k, $user[$col ?? ('bp_' . $k)] ?? '')); ?>

<p class="text-sm text-zinc-400 mb-6 max-w-2xl">
    O Blueprint profissional resume como você trabalha. Tudo é opcional — preencha o que fizer sentido e aparecerá organizado no seu perfil.
</p>

<form method="post" action="<?= url('dashboard/blueprint') ?>" class="max-w-2xl space-y-6">
    <?= csrf_field() ?>

    <div class="glass-strong rounded-2xl p-5 space-y-4">
        <h2 class="font-semibold text-white">Cabeçalho</h2>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Título profissional</label>
            <input name="headline" value="<?= e(old('headline', $user['headline'] ?? '')) ?>" class="field" placeholder="Designer de produto · Freelancer">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-zinc-400 mb-1">Foco atual</label>
                <input name="current_focus" value="<?= e(old('current_focus', $user['bp_current_focus'] ?? '')) ?>" class="field" placeholder="Apps de saúde">
            </div>
            <div>
                <label class="block text-xs text-zinc-400 mb-1">Status dos projetos</label>
                <input name="project_status" value="<?= e(old('project_status', $user['bp_project_status'] ?? '')) ?>" class="field" placeholder="Aceitando novos">
            </div>
        </div>
    </div>

    <div class="glass-strong rounded-2xl p-5 space-y-4">
        <h2 class="font-semibold text-white">Disponibilidade</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-zinc-400 mb-1">Disponibilidade</label>
                <input name="availability" value="<?= $val('availability') ?>" class="field" placeholder="2 vagas no mês">
            </div>
            <div>
                <label class="block text-xs text-zinc-400 mb-1">Horário de trabalho</label>
                <input name="working_hours" value="<?= $val('working_hours') ?>" class="field" placeholder="Seg–Sex, 9h–18h (BRT)">
            </div>
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Preferências de contato</label>
            <textarea name="contact_prefs" rows="2" class="field resize-none" placeholder="Prefiro e-mail para propostas; respondo em até 24h."><?= $val('contact_prefs') ?></textarea>
        </div>
    </div>

    <div class="glass-strong rounded-2xl p-5 space-y-4">
        <h2 class="font-semibold text-white">Como você trabalha</h2>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Valores</label>
            <textarea name="values" rows="3" class="field resize-none" placeholder="Transparência, prazos realistas, foco no essencial."><?= $val('values') ?></textarea>
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Método de trabalho</label>
            <textarea name="work_method" rows="3" class="field resize-none" placeholder="Descoberta → protótipo → entregas semanais."><?= $val('work_method') ?></textarea>
        </div>
    </div>

    <div class="glass-strong rounded-2xl p-5 space-y-4">
        <h2 class="font-semibold text-white">Compatibilidade & expectativas</h2>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Para quem é ideal</label>
            <textarea name="client_compat" rows="3" class="field resize-none" placeholder="Startups em estágio inicial que valorizam design."><?= $val('client_compat') ?></textarea>
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">O que esperar</label>
            <textarea name="expectations" rows="3" class="field resize-none" placeholder="Comunicação clara, escopo combinado, sem surpresas."><?= $val('expectations') ?></textarea>
        </div>
    </div>

    <button class="px-6 py-2.5 rounded-full bg-orange-600 text-white text-sm font-medium hover:bg-orange-500 transition">Salvar Blueprint</button>
</form>
