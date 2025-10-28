<?php
include "conexion.php";

$id = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$stmt = $conexion->prepare("SELECT id, vendedor, direccion, fechaventa FROM ventas WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();
if (!$row) { echo "No encontrado"; exit; }
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
  </head>
  <body>
    <div class="container py-5">
      <a href="index.php" class="btn btn-secondary mb-3">Volver</a>
      <main class="card-modern">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="mb-0">Venta #<?php echo htmlspecialchars($row['id']); ?></h4>
          <div class="small-muted">Detalle del registro</div>
        </div>
        <dl class="row">
          <dt class="col-sm-3">Vendedor</dt>
          <dd class="col-sm-9"><?php echo htmlspecialchars($row['vendedor']); ?></dd>

          <dt class="col-sm-3">Dirección</dt>
          <dd class="col-sm-9"><?php echo htmlspecialchars($row['direccion']); ?></dd>

          <dt class="col-sm-3">Fecha</dt>
          <dd class="col-sm-9"><?php echo htmlspecialchars($row['fechaventa']); ?></dd>
        </dl>
      </main>
    </div>
    <script src="assets/js/app.js"></script>
  </body>
</html>