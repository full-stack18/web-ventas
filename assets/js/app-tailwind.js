// Script específico para index-tailwind.php
document.addEventListener('DOMContentLoaded', () => {
  // ============== TEMA OSCURO/CLARO ==============
  const html = document.documentElement;
  
  const toggleTailwindTheme = (isDark) => {
    if (isDark) {
      html.classList.add('dark');
    } else {
      html.classList.remove('dark');
    }
    localStorage.setItem('tailwind-theme-dark', isDark ? '1' : '0');
  };

  // Inicializar tema
  const stored = localStorage.getItem('tailwind-theme-dark');
  if (stored === null) {
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    toggleTailwindTheme(prefersDark);
  } else {
    toggleTailwindTheme(stored === '1');
  }

  // ============== MODAL ==============
  const openNewBtn = document.getElementById('openNewBtnTailwind');
  const newModal = document.getElementById('newModal');
  const modalInner = document.getElementById('modalInner');

  if (!openNewBtn || !newModal || !modalInner) {
    console.error('Elementos del modal no encontrados');
    return;
  }

  // Función para abrir modal
  const handleOpenNewClick = async (e) => {
    e.preventDefault();
    
    modalInner.innerHTML = '<div style="text-align: center; color: #9ca3af; padding: 2rem;">Cargando formulario...</div>';
    newModal.style.display = 'flex';
    
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
      
      // Focus al primer input
      const firstInput = modalInner.querySelector('input[name="vendedor"]');
      if (firstInput) firstInput.focus();
      
      // Inicializar eventos del formulario
      initializeModalForm();
      
    } catch (err) {
      console.error('Error al cargar formulario:', err);
      modalInner.innerHTML = '<div style="padding: 2rem; text-align: center;"><p style="color: #ef4444; font-weight: 600; margin-bottom: 1rem;">❌ Error al cargar</p><p style="font-size: 0.9rem; color: #9ca3af; margin-bottom: 1.5rem;">' + (err.message || '') + '</p><button onclick="document.getElementById(\'newModal\').style.display=\'none\'" style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer;">Cerrar</button></div>';
    }
  };

  // Función para inicializar el formulario
  const initializeModalForm = () => {
    const form = modalInner.querySelector('#modalForm');
    const cancelBtn = modalInner.querySelector('#modalCancel');
    
    // Botón cancelar
    if (cancelBtn) {
      cancelBtn.addEventListener('click', () => {
        newModal.style.display = 'none';
      });
    }
    
    // Envío del formulario
    if (form) {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const cancelBtn2 = form.querySelector('#modalCancel');
        
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '⏳ Guardando...';
        }
        if (cancelBtn2) cancelBtn2.disabled = true;
        
        try {
          const formData = new FormData(form);
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
              newModal.style.display = 'none';
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
  };

  // Event listener para abrir modal
  if (openNewBtn) {
    openNewBtn.addEventListener('click', handleOpenNewClick);
  }

  // Cerrar modal al hacer click fuera
  newModal.addEventListener('click', (e) => {
    if (e.target === newModal) {
      newModal.style.display = 'none';
    }
  });

  // Cerrar con tecla Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && newModal.style.display === 'flex') {
      newModal.style.display = 'none';
    }
  });
});
