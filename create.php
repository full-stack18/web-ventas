<?php
include "conexion.php";
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
  </head>
  <body>
    <div class="container py-5 create-screen">
      <a href="index.php" class="btn btn-back">← Volver</a>
      <main class="card-modern form-panel bg-animated" style="position:relative;overflow:hidden;">
        <div class="accent-bubble bubble-1" aria-hidden="true"></div>
        <div class="accent-bubble bubble-2" aria-hidden="true"></div>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h1 class="h4 mb-0">Nueva venta</h1>
            <div class="small-muted">Rellena los datos para agregar una fila</div>
          </div>
        </div>
        <div class="row g-4">
          <div class="col-12 col-lg-5 d-flex align-items-center justify-content-center">
            <div style="max-width:300px;">
              <?php include __DIR__ . '/assets/images/systems-center.svg'; ?>
              <p class="small-muted mt-2" style="color:var(--text)">Modo terminal: ingresa la venta y pulsa Guardar.</p>
            </div>
          </div>
          <div class="col-12 col-lg-7">
            <?php include __DIR__ . '/create_fragment.php'; ?>
          </div>
        </div>
      </main>

    </div>
    <script src="assets/js/app.js"></script>
  </body>
</html>