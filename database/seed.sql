-- Originium — temas de fábrica (paleta fria, glassmorphism discreto)
-- Execute depois de schema.sql.

INSERT INTO themes (name, slug, bg_class, card_class, text_class, accent_class, is_default) VALUES
('Glacier',  'glacier',
    'bg-gradient-to-b from-slate-900 via-slate-950 to-black',
    'bg-white/5 border border-white/10 hover:bg-white/10 backdrop-blur',
    'text-slate-100', 'text-sky-300', 1),

('Midnight', 'midnight',
    'bg-gradient-to-b from-[#0b1020] via-[#0a0e1a] to-black',
    'bg-indigo-500/10 border border-indigo-400/20 hover:bg-indigo-500/20 backdrop-blur',
    'text-indigo-50', 'text-indigo-300', 0),

('Aurora',   'aurora',
    'bg-gradient-to-b from-teal-950 via-slate-950 to-black',
    'bg-teal-400/10 border border-teal-300/20 hover:bg-teal-400/20 backdrop-blur',
    'text-teal-50', 'text-teal-300', 0),

('Graphite', 'graphite',
    'bg-neutral-950',
    'bg-neutral-900 border border-neutral-800 hover:border-neutral-700',
    'text-neutral-100', 'text-zinc-400', 0),

('Frost',    'frost',
    'bg-gradient-to-b from-sky-50 to-slate-100',
    'bg-white/70 border border-white hover:bg-white backdrop-blur shadow-sm',
    'text-slate-800', 'text-sky-600', 0),

('Arctic',   'arctic',
    'bg-gradient-to-b from-zinc-100 to-white',
    'bg-white border border-zinc-200 hover:border-sky-300 shadow-sm',
    'text-zinc-900', 'text-sky-600', 0);
