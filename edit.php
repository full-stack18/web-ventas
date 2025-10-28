<?php
include "conexion.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php'); exit;
}

$stmt = $conexion->prepare("SELECT id, vendedor, direccion, fechaventa FROM ventas WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) {
    echo "Registro no encontrado"; exit;
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
  </head>
  <body>
    <div class="container py-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h4 mb-0">Editar venta #<?php echo htmlspecialchars($row['id']); ?></h1>
          <div class="small-muted">Modifica los campos necesarios</div>
        </div>
        <a href="index.php" class="btn btn-secondary">Volver</a>
      </div>

      <main class="card-modern">
        <form action="update.php" method="post">
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Vendedor</label>
              <input name="vendedor" required class="form-control" value="<?php echo htmlspecialchars($row['vendedor']); ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Dirección</label>
              <input name="direccion" required class="form-control" value="<?php echo htmlspecialchars($row['direccion']); ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Fecha de venta</label>
              <input type="date" name="fechaventa" required class="form-control" value="<?php echo htmlspecialchars($row['fechaventa']); ?>">
            </div>
          </div>
          <div class="mt-4">
            <button class="btn btn-primary">Actualizar</button>
          </div>
        </form>
      </main>

    </div>
    <script src="assets/js/app.js"></script>
  </body>
</html>