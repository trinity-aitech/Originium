<?php /* Logotipo Originium: triângulo wireframe laranja + wordmark */ ?>
<a href="<?= url('/') ?>" class="inline-flex items-center gap-2.5 group">
    <svg viewBox="0 0 100 100" class="w-8 h-8 shrink-0" aria-hidden="true">
        <defs>
            <linearGradient id="originium-mark" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#fbbf24"/>
                <stop offset="0.5" stop-color="#f97316"/>
                <stop offset="1" stop-color="#ea580c"/>
            </linearGradient>
        </defs>
        <g fill="none" stroke="url(#originium-mark)" stroke-width="2.4" stroke-linejoin="round" stroke-linecap="round">
            <path d="M50 7 L92 88 L8 88 Z"/>
            <path d="M29 47 L71 47 L50 88 Z"/>
            <path d="M50 7 L50 88"/>
            <path d="M8 88 L71 47"/>
            <path d="M92 88 L29 47"/>
            <path d="M43 57 L57 57 L50 70 Z"/>
        </g>
    </svg>
    <span class="font-display text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">Originium</span>
</a>
