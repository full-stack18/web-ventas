<?php
// Fragmento de formulario reutilizable para modal o página create.php
?>
<div class="modal-content-fragment">
  <h5 class="mb-3">Nueva venta</h5>
  <form id="modalForm" action="store.php" method="post">
    <div class="mb-3">
      <label class="form-label">Vendedor</label>
      <input name="vendedor" required class="form-control" placeholder="Nombre del vendedor">
    </div>
    <div class="mb-3">
      <label class="form-label">Dirección</label>
      <input name="direccion" required class="form-control" placeholder="Dirección">
    </div>
    <div class="mb-3">
      <label class="form-label">Fecha de venta</label>
      <input type="date" name="fechaventa" required class="form-control">
    </div>
    <div class="d-flex justify-content-end gap-2">
      <button type="button" class="btn btn-secondary" id="modalCancel">Cancelar</button>
      <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>