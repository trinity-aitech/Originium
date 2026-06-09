// Originium — JS leve em vanilla (sem dependências).

(function () {
  'use strict';

  // Menu mobile
  document.addEventListener('click', function (e) {
    const toggle = e.target.closest('[data-menu-toggle]');
    if (toggle) {
      const menu = document.querySelector('[data-menu]');
      if (menu) menu.classList.toggle('hidden');
    }
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
