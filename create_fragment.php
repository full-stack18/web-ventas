<?php
// Fragmento de formulario reutilizable para modal o página create.php
?>
<div class="modal-content-fragment">
  <h5 class="mb-3" style="font-size: 1.4rem; font-weight: 600; margin-bottom: 1.5rem; color: #fff;">Nueva venta</h5>
  <form id="modalForm" action="store.php" method="post">
    <div class="mb-3">
      <label class="form-label" style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #d1d5db;">Vendedor</label>
      <input name="vendedor" required class="form-control" placeholder="Nombre del vendedor" style="width: 100%; border: 1px solid #4b5563; border-radius: 6px; padding: 0.75rem 1rem; background: #111827; color: #fff; font-size: 0.95rem;">
    </div>
    <div class="mb-3">
      <label class="form-label" style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #d1d5db;">Dirección</label>
      <input name="direccion" required class="form-control" placeholder="Dirección" style="width: 100%; border: 1px solid #4b5563; border-radius: 6px; padding: 0.75rem 1rem; background: #111827; color: #fff; font-size: 0.95rem;">
    </div>
    <div class="mb-3">
      <label class="form-label" style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #d1d5db;">Fecha de venta</label>
      <input type="date" name="fechaventa" required class="form-control" style="width: 100%; border: 1px solid #4b5563; border-radius: 6px; padding: 0.75rem 1rem; background: #111827; color: #fff; font-size: 0.95rem;">
    </div>
    <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
      <button type="button" class="btn btn-secondary" id="modalCancel" style="padding: 0.625rem 1.25rem; border-radius: 6px; font-weight: 500; border: none; background: #6b7280; color: white; cursor: pointer;">Cancelar</button>
      <button type="submit" class="btn btn-primary" style="padding: 0.625rem 1.25rem; border-radius: 6px; font-weight: 500; border: none; background: #3b82f6; color: white; cursor: pointer;">Guardar</button>
    </div>
  </form>
</div>