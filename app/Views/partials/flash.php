<?php
$success = \App\Core\Session::getFlash('success');
$error   = \App\Core\Session::getFlash('error');
?>
<?php if ($success): ?>
    <div class="flash-msg mb-5 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
        <?= e($success) ?>
    </div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="flash-msg mb-5 rounded-2xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-300">
        <?= e($error) ?>
    </div>
<?php endif; ?>
