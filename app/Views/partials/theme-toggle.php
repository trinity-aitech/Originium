<?php /* Botão redondo e discreto para alternar tema claro/escuro */ ?>
<button type="button" data-theme-toggle aria-label="Alternar tema claro/escuro"
        class="w-9 h-9 grid place-items-center rounded-full border border-black/10 dark:border-white/15
               text-zinc-600 dark:text-zinc-300 hover:bg-black/5 dark:hover:bg-white/10 transition">
    <!-- Lua (modo claro: oferece escuro) -->
    <svg class="w-[18px] h-[18px] block dark:hidden" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/>
    </svg>
    <!-- Sol (modo escuro: oferece claro) -->
    <svg class="w-[18px] h-[18px] hidden dark:block" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="4"/>
        <path stroke-linecap="round" d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
    </svg>
</button>
