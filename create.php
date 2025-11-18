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
            <div style="max-width:320px; width:100%; text-align:center;">
              <svg viewBox="0 0 200 180" style="max-width:100%; height:auto; margin-bottom:20px; display:block; margin-left:auto; margin-right:auto;" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <style>
                    @media (prefers-color-scheme: dark) {
                      .svg-stroke { stroke: #4da3ff; }
                      .svg-accent { stroke: #4da3ff; }
                    }
                    .svg-stroke { stroke: #0d6efd; }
                    .svg-accent { stroke: #198754; }
                  </style>
                </defs>
                <!-- Background rectangle -->
                <rect width="200" height="180" fill="none" class="svg-stroke" stroke-width="2" rx="8"/>
                <!-- Monitor frame -->
                <rect x="20" y="20" width="160" height="110" fill="none" class="svg-stroke" stroke-width="2" rx="4"/>
                <!-- Screen content - grid pattern -->
                <g class="svg-stroke" stroke-width="1" opacity="0.6">
                  <line x1="35" y1="35" x2="165" y2="35"/>
                  <line x1="35" y1="50" x2="165" y2="50"/>
                  <line x1="35" y1="65" x2="165" y2="65"/>
                  <line x1="35" y1="80" x2="165" y2="80"/>
                  <line x1="35" y1="95" x2="165" y2="95"/>
                  <line x1="35" y1="110" x2="165" y2="110"/>
                </g>
                <!-- Cursor blink indicator -->
                <rect x="40" y="115" width="8" height="6" class="svg-stroke" rx="1"/>
                <!-- Monitor stand -->
                <rect x="80" y="135" width="40" height="8" class="svg-stroke" rx="2"/>
                <!-- Stand base -->
                <ellipse cx="100" cy="151" rx="35" ry="6" fill="none" class="svg-stroke" stroke-width="2"/>
                <!-- Checkmark decoration -->
                <g class="svg-accent" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M 165 155 L 175 165 L 190 145"/>
                </g>
              </svg>
              <p class="small-muted mt-2" style="color:var(--text); margin:1rem 0 0 0;">Modo terminal: ingresa la venta y pulsa Guardar.</p>
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