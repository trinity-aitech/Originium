<?php
$isEdit = $editing !== null;
$val = fn($k, $d = '') => e(old($k, $editing[$k] ?? $d));
$enabled = (int) $user['contact_enabled'] === 1;
$route = 'dashboard/contact';
?>

<!-- Liga/desliga -->
<div class="glass rounded-2xl p-5 mb-6 flex items-center justify-between gap-4">
    <div>
        <p class="text-white font-medium">Formulário de contato</p>
        <p class="text-sm text-zinc-500"><?= $enabled ? 'Ativo — visível no seu perfil.' : 'Desativado.' ?></p>
    </div>
    <form method="post" action="<?= url($route . '/toggle') ?>">
        <?= csrf_field() ?>
        <button class="px-5 py-2 rounded-full text-sm font-medium transition <?= $enabled ? 'glass text-white hover:bg-white/10' : 'bg-orange-600 text-white hover:bg-orange-500' ?>">
            <?= $enabled ? 'Desativar' : 'Ativar' ?>
        </button>
    </form>
</div>

<div class="grid lg:grid-cols-5 gap-6">
    <!-- Editor de campos -->
    <form method="post" action="<?= url($isEdit ? $route . '/fields/' . $editing['id'] : $route . '/fields') ?>" class="lg:col-span-2 glass-strong rounded-2xl p-5 space-y-3 h-fit">
        <?= csrf_field() ?>
        <h2 class="font-semibold text-white"><?= $isEdit ? 'Editar campo' : 'Novo campo' ?></h2>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Rótulo</label>
            <input name="label" value="<?= $val('label') ?>" class="field" placeholder="Seu nome">
            <?php if ($m = error_for('label')): ?><p class="text-xs text-rose-400 mt-1"><?= e($m) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Tipo</label>
            <select name="field_type" class="field">
                <?php foreach ($types as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= ($editing['field_type'] ?? 'text') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs text-zinc-400 mb-1">Placeholder (opcional)</label>
            <input name="placeholder" value="<?= $val('placeholder') ?>" class="field" placeholder="Ex.: João Silva">
        </div>
        <label class="flex items-center gap-2 text-sm text-zinc-300">
            <input type="checkbox" name="is_required" value="1" <?= ($isEdit && (int) $editing['is_required'] === 1) ? 'checked' : '' ?> class="accent-orange-500">
            Obrigatório
        </label>
        <div class="flex items-center gap-3 pt-1">
            <button class="px-5 py-2 rounded-full bg-orange-600 text-white text-sm font-medium hover:bg-orange-500 transition"><?= $isEdit ? 'Salvar' : 'Adicionar' ?></button>
            <?php if ($isEdit): ?><a href="<?= url($route) ?>" class="text-sm text-zinc-400 hover:text-white">Cancelar</a><?php endif; ?>
        </div>
    </form>

    <div class="lg:col-span-3 space-y-2">
        <h3 class="text-sm font-semibold text-zinc-300">Campos</h3>
        <?php if (empty($fields)): ?>
            <div class="glass rounded-2xl p-6 text-center text-sm text-zinc-500">Sem campos. Adicione ao menos um (ex.: Nome, E-mail, Mensagem).</div>
        <?php else: foreach ($fields as $i => $item): ?>
            <div class="glass rounded-2xl px-4 py-3 flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-zinc-200"><?= e($item['label']) ?>
                        <span class="text-xs text-zinc-500">· <?= e($types[$item['field_type']] ?? $item['field_type']) ?></span>
                        <?php if ((int) $item['is_required'] === 1): ?><span class="text-xs text-orange-400">· obrigatório</span><?php endif; ?>
                    </p>
                </div>
                <?php
                $id = (int) $item['id']; $first = $i === 0; $last = $i === count($fields) - 1;
                $toggle = false; $active = false; $editUrl = url($route . '?edit=' . $id);
                $route_actions = $route . '/fields';
                // row_actions usa $route para montar as URLs; ajustamos para o sub-recurso
                $routeBackup = $route; $route = $route_actions;
                require BASE_DIR . '/app/Views/partials/row_actions.php';
                $route = $routeBackup;
                ?>
            </div>
        <?php endforeach; endif; ?>

        <!-- Caixa de entrada -->
        <h3 class="text-sm font-semibold text-zinc-300 mt-6">Mensagens recebidas</h3>
        <?php if (empty($messages)): ?>
            <div class="glass rounded-2xl p-6 text-center text-sm text-zinc-500">Nenhuma mensagem ainda.</div>
        <?php else: foreach ($messages as $msg): ?>
            <div class="glass rounded-2xl px-4 py-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-zinc-500"><?= e(date('d/m/Y H:i', strtotime($msg['created_at']))) ?></span>
                    <form method="post" action="<?= url($route . '/messages/' . $msg['id'] . '/delete') ?>" data-confirm="Apagar mensagem?"><?= csrf_field() ?><button class="text-zinc-400 hover:text-rose-400 text-xs">apagar</button></form>
                </div>
                <?php foreach ($msg['data'] as $label => $value): ?>
                    <p class="text-sm text-zinc-200"><span class="text-zinc-500"><?= e($label) ?>:</span> <?= e($value) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>
