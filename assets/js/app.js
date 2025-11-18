// Guarda y aplica tema (claro/oscuro)
(() => {
  const root = document.documentElement;
  const body = document.body;
  
  const toggleTheme = (isDark) => {
    if (isDark) {
      body.classList.add('dark');
      root.style.setProperty('--bg-primary', '#1e1e1e');
      root.style.setProperty('--bg-secondary', '#2d2d2d');
      root.style.setProperty('--text', '#e8e8e8');
      root.style.setProperty('--text-muted', '#a0a0a0');
      root.style.setProperty('--border', '#404040');
      root.style.setProperty('--accent', '#4da3ff');
    } else {
      body.classList.remove('dark');
      root.style.setProperty('--bg-primary', '#ffffff');
      root.style.setProperty('--bg-secondary', '#f8f9fa');
      root.style.setProperty('--text', '#333333');
      root.style.setProperty('--text-muted', '#6c757d');
      root.style.setProperty('--border', '#dee2e6');
      root.style.setProperty('--accent', '#0d6efd');
    }
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

  document.addEventListener('DOMContentLoaded', () => {
    // Buscar o crear botón toggle
    let btn = document.getElementById('themeToggleBtn');
    if (!btn) {
      const container = document.querySelector('.app-header');
      if (container) {
        btn = document.createElement('button');
        btn.id = 'themeToggleBtn';
        btn.className = 'theme-toggle';
        btn.type = 'button';
        btn.setAttribute('aria-label', 'Alternar modo oscuro');
        container.appendChild(btn);
      }
    }

    if (!btn) return;

    // Helper para actualizar icono y tooltip
    const setIcon = (isDark) => {
      if (isDark) {
        btn.innerHTML = `
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="4"></circle>
            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
          </svg>`;
        btn.title = 'Cambiar a modo claro';
        btn.setAttribute('aria-pressed', 'true');
      } else {
        btn.innerHTML = `
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
          </svg>`;
        btn.title = 'Cambiar a modo oscuro';
        btn.setAttribute('aria-pressed', 'false');
      }
    };

    // Establecer icono inicial
    setIcon(document.body.classList.contains('dark'));

    // Click handler
    btn.addEventListener('click', () => {
      const isDark = !document.body.classList.contains('dark');
      toggleTheme(isDark);
      setIcon(isDark);
    });

    // Modal dynamic loader
    const openNewBtn = document.getElementById('openNewBtn');
    const openNewBtnTailwind = document.getElementById('openNewBtnTailwind');
    const newModal = document.getElementById('newModal');
    const modalInner = document.getElementById('modalInner');

    const initializeModal = () => {
      const modalInner = document.getElementById('modalInner');
      const newModal = document.getElementById('newModal');
      
      if (!newModal || !modalInner) {
        console.error('Modal elements not found');
        return false;
      }
      
      const cancelBtn = modalInner.querySelector('#modalCancel');
      if (cancelBtn) {
        cancelBtn.addEventListener('click', (ev) => {
          ev.preventDefault();
          newModal.classList.remove('show');
          newModal.setAttribute('aria-hidden', 'true');
        });
      }

      const modalForm = modalInner.querySelector('#modalForm');
      if (modalForm) {
        modalForm.addEventListener('submit', async (ev) => {
          ev.preventDefault();
          const formData = new FormData(modalForm);
          const submitBtn = modalForm.querySelector('button[type="submit"]');
          const cancelBtn2 = modalForm.querySelector('button#modalCancel');
          
          if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ Guardando...';
          }
          if (cancelBtn2) cancelBtn2.disabled = true;
          
          try {
            const res = await fetch('store.php', {
              method: 'POST',
              body: formData,
              headers: { 'Accept': 'application/json' }
            });
            
            if (!res.ok) {
              throw new Error(`Error HTTP ${res.status}`);
            }
            
            const json = await res.json();
            
            if (json && json.success) {
              if (submitBtn) submitBtn.innerHTML = '✔ Guardado';
              setTimeout(() => {
                newModal.classList.remove('show');
                newModal.setAttribute('aria-hidden', 'true');
                window.location.reload();
              }, 400);
            } else {
              const err = (json && json.error) ? json.error : 'Error desconocido';
              alert('Error: ' + err);
              if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Guardar';
              }
              if (cancelBtn2) cancelBtn2.disabled = false;
            }
          } catch (err) {
            console.error('Error:', err);
            alert('Error de red o servidor: ' + err.message);
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = 'Guardar';
            }
            if (cancelBtn2) cancelBtn2.disabled = false;
          }
        });
      }
      
      return true;
    };

    const handleOpenNewClick = async (e) => {
      e.preventDefault();
      const newModal = document.getElementById('newModal');
      const modalInner = document.getElementById('modalInner');
      
      if (!newModal || !modalInner) {
        alert('Error: no se encontraron elementos del modal');
        return;
      }
      
      modalInner.innerHTML = '<div style="padding: 2rem; text-align: center; color: var(--text-muted);">Cargando formulario...</div>';
      newModal.classList.add('show');
      newModal.setAttribute('aria-hidden', 'false');
      
      try {
        const res = await fetch('create_fragment.php');
        if (!res.ok) {
          throw new Error('No se pudo cargar el formulario (HTTP ' + res.status + ')');
        }
        
        const html = await res.text();
        if (!html || html.trim().length === 0) {
          throw new Error('Respuesta vacía del servidor');
        }
        
        modalInner.innerHTML = html;
        
        const firstInput = modalInner.querySelector('input[name="vendedor"]');
        if (firstInput) firstInput.focus();
        
        initializeModal();
        
      } catch (err) {
        console.error('Error al cargar formulario:', err);
        modalInner.innerHTML = '<div style="padding: 2rem; text-align: center;"><p style="color: var(--danger); font-weight: 600; margin-bottom: 1rem;">❌ Error al cargar el formulario</p><p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">' + (err.message || '') + '</p><button onclick="document.getElementById(\'newModal\').classList.remove(\'show\'); document.getElementById(\'newModal\').setAttribute(\'aria-hidden\', \'true\');" class="btn btn-primary" style="margin-top: 0.5rem;">Cerrar</button></div>';
      }
    };

    if (openNewBtn) {
      openNewBtn.addEventListener('click', handleOpenNewClick);
    }

    if (openNewBtnTailwind) {
      openNewBtnTailwind.addEventListener('click', handleOpenNewClick);
    }

    if (newModal) {
      newModal.addEventListener('click', (e) => {
        if (e.target === newModal) {
          newModal.classList.remove('show');
          newModal.setAttribute('aria-hidden', 'true');
        }
      });
    }
  });
})();
