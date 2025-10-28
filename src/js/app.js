// Guarda y aplica tema (claro/oscuro)
(() => {
  const root = document.documentElement;
  const body = document.body;
  const toggleTheme = (isDark) => {
    if (isDark) body.classList.add('dark'); else body.classList.remove('dark');
    localStorage.setItem('theme-dark', isDark ? '1' : '0');
  }

  // Inicializar según preferencia
  const stored = localStorage.getItem('theme-dark');
  if (stored === null) {
    // usar preferencia del sistema
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    toggleTheme(prefersDark);
  } else {
    toggleTheme(stored === '1');
  }

  // Crear botón toggle si existe contenedor
  document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.app-header');
    if (!container) return;
    // preferir un botón estático si existe
    let btn = document.getElementById('themeToggleBtn');
    if (!btn) {
      btn = document.createElement('button');
      btn.className = 'theme-toggle';
      btn.type = 'button';
      btn.setAttribute('aria-label', 'Alternar modo oscuro');
      container.appendChild(btn);
    }
    // helper para actualizar icono y tooltip
    const setIcon = (isDark) => {
      if (isDark) {
        // mostrar icono de sol para indicar "volver a claro"
        btn.innerHTML = `
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="4"></circle>
            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
          </svg>`;
        btn.title = 'Cambiar a modo claro';
        btn.setAttribute('aria-pressed', 'true');
      } else {
        // mostrar icono de luna para indicar "activar oscuro"
        btn.innerHTML = `
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
          </svg>`;
        btn.title = 'Cambiar a modo oscuro';
        btn.setAttribute('aria-pressed', 'false');
      }
    };

    // establecer icono inicial
    setIcon(document.body.classList.contains('dark'));

    btn.addEventListener('click', () => {
      const isDark = !document.body.classList.contains('dark');
      toggleTheme(isDark);
      setIcon(isDark);
    });

    // Nota: el ordenamiento debe controlarse desde los botones a la derecha de la tabla (servidor-side).
    // No se añaden manejadores de click en los encabezados para evitar ordenamiento cliente inesperado.

    // Mostrar animación del panel de creación (si existe)
    const formPanel = document.querySelector('.form-panel');
    if (formPanel) {
      // small delay for nicer entrance
      requestAnimationFrame(() => setTimeout(() => formPanel.classList.add('show'), 60));
    }

    // Modal dynamic loader: cargar fragmento create_fragment.php
    const openNewBtn = document.getElementById('openNewBtn');
    const newModal = document.getElementById('newModal');
    const modalInner = document.getElementById('modalInner');
    const openTailwindBtn = document.getElementById('openTailwindBtn');

    if (openNewBtn && newModal && modalInner) {
      openNewBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        // mostrar spinner mientras carga (usar variante small)
        modalInner.innerHTML = '<div class="spinner small" aria-hidden="true"></div>';
        newModal.classList.add('show');
        try {
          const res = await fetch('create_fragment.php');
          if (!res.ok) throw new Error('No se pudo cargar el formulario (HTTP ' + res.status + ')');
          const html = await res.text();
          // si por alguna razón recibimos un HTML vacío, usar fallback
          if (!html || html.trim().length === 0) {
            const tpl = document.getElementById('modalFormTemplate');
            if (tpl) modalInner.innerHTML = tpl.innerHTML; else modalInner.innerHTML = html;
          } else {
            modalInner.innerHTML = html;
          }
          // focus al primer input
          const firstInput = modalInner.querySelector('input[name="vendedor"]');
          if (firstInput) firstInput.focus();

          // cancelar
          const cancelBtn = modalInner.querySelector('#modalCancel');
          if (cancelBtn) cancelBtn.addEventListener('click', (ev) => { ev.preventDefault(); newModal.classList.remove('show'); });

          // submit: permitimos envío normal por ahora (store.php) y cerramos modal si la respuesta redirige
          const modalForm = modalInner.querySelector('#modalForm');
          if (modalForm) {
            modalForm.addEventListener('submit', async (ev) => {
              ev.preventDefault();
              const formData = new FormData(modalForm);
              const submitBtn = modalForm.querySelector('button[type="submit"]');
              const cancelBtn2 = modalForm.querySelector('button#modalCancel');
              if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Guardando...'; }
              if (cancelBtn2) cancelBtn2.disabled = true;
              try {
                const res = await fetch('store.php', { method: 'POST', body: formData, headers: { 'Accept': 'application/json' } });
                const json = await res.json();
                if (json && json.success) {
                  // mostrar breve confirmación y recargar
                  if (submitBtn) submitBtn.textContent = '✔';
                  setTimeout(() => {
                    newModal.classList.remove('show');
                    window.location.reload();
                  }, 400);
                } else {
                  const err = (json && json.error) ? json.error : 'Error al guardar';
                  alert('Error: ' + err);
                  if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Guardar'; }
                  if (cancelBtn2) cancelBtn2.disabled = false;
                }
              } catch (err) {
                alert('Error de red al guardar');
                if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Guardar'; }
                if (cancelBtn2) cancelBtn2.disabled = false;
              }
            });
          }
        } catch (err) {
          // intentar fallback a template si existe
          const tpl = document.getElementById('modalFormTemplate');
          if (tpl) {
            modalInner.innerHTML = tpl.innerHTML;
          } else {
            modalInner.innerHTML = '<div class="p-3 text-center">\n            <p>Error al cargar el formulario.</p>\n            <p class="small-muted">' + (err.message || '') + '</p>\n          </div>';
          }
        }
      });
    }

    if (openTailwindBtn) {
      openTailwindBtn.addEventListener('click', (e) => { e.preventDefault(); window.location.href = 'index-tailwind.php'; });
    }

    // Modal behavior is handled by the dynamic loader above.
    // Ensure Escape closes the modal backdrop when open
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') {
      const m = document.getElementById('newModal'); if (m && m.classList.contains('show')) m.classList.remove('show');
    } });
    // close when clicking on backdrop (delegated)
    document.addEventListener('click', (e) => {
      const m = document.getElementById('newModal');
      if (!m) return;
      if (e.target === m) m.classList.remove('show');
    });
  });

  // No hay sortTable() — el ordenamiento se realiza en el servidor mediante los botones de la UI.
})();
