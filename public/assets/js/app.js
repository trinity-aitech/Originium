// Originium — JS leve em vanilla (sem dependências).

(function () {
  'use strict';

  // Alternar tema claro/escuro (persistido em localStorage)
  document.addEventListener('click', function (e) {
    if (e.target.closest('[data-theme-toggle]')) {
      const isDark = document.documentElement.classList.toggle('dark');
      try { localStorage.setItem('theme', isDark ? 'dark' : 'light'); } catch (err) {}
    }
  });

  // Menu mobile
  document.addEventListener('click', function (e) {
    const toggle = e.target.closest('[data-menu-toggle]');
    if (toggle) {
      const menu = document.querySelector('[data-menu]');
      if (menu) menu.classList.toggle('hidden');
    }
  });

  // Galeria do perfil: navegação anterior / próxima
  document.addEventListener('click', function (e) {
    const prev = e.target.closest('[data-gallery-prev]');
    const next = e.target.closest('[data-gallery-next]');
    if (!prev && !next) return;
    const wrap = e.target.closest('[data-gallery]');
    const track = wrap && wrap.querySelector('[data-gallery-track]');
    if (!track) return;
    track.scrollBy({ left: next ? track.clientWidth : -track.clientWidth, behavior: 'smooth' });
  });

  // Botão de copiar (ex.: link público do perfil)
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-copy]');
    if (!btn) return;
    const value = btn.getAttribute('data-copy');
    navigator.clipboard.writeText(value).then(function () {
      const original = btn.getAttribute('data-label') || btn.textContent;
      btn.textContent = 'Copiado!';
      setTimeout(function () { btn.textContent = original; }, 1500);
    });
  });

  // Confirmação antes de deletar
  document.addEventListener('submit', function (e) {
    const form = e.target;
    if (form.matches('[data-confirm]')) {
      if (!window.confirm(form.getAttribute('data-confirm'))) {
        e.preventDefault();
      }
    }
  });

  // Esconde flash após alguns segundos
  document.querySelectorAll('.flash-msg').forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity .4s ease';
      el.style.opacity = '0';
      setTimeout(function () { el.remove(); }, 400);
    }, 4000);
  });
})();
