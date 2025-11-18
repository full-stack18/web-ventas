<?php
include "conexion.php";
// Simple query para mostrar ventas
$res = $conexion->query("SELECT * FROM ventas ORDER BY id ASC");
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Web Ventas — Tailwind</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-green-400 min-h-screen" style="padding-top: 2rem;">
  <div class="container mx-auto px-4" style="margin-top: 2rem;">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-green-400 rounded-lg flex items-center justify-center font-bold text-black">V</div>
        <div>
          <h1 class="text-2xl font-semibold">Web Ventas (Tailwind)</h1>
          <p class="text-sm text-green-300">Versión alternativa con Tailwind</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
          <button id="openNewBtnTailwind" class="px-3 py-2 bg-green-600 text-black rounded cursor-pointer hover:bg-green-700">Nueva venta</button>
          <a href="index.php" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Bootstrap</a>
          <a href="registrar.php" class="px-3 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Registrar</a>
        </div>
    </div>

    <div class="bg-black/60 rounded-xl p-6">
      <h2 class="text-xl mb-4">Listado de ventas</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full text-left">
          <thead>
            <tr class="border-b border-green-800">
              <th class="py-2">ID</th>
              <th class="py-2">Vendedor</th>
              <th class="py-2">Dirección</th>
              <th class="py-2">Fecha</th>
            </tr>
          </thead>
            <tbody>
            <?php if ($res && $res->num_rows): while($r = $res->fetch_assoc()): ?>
            <tr class="border-b border-green-900/30 hover:bg-green-900/10">
              <td class="py-2"><?php echo htmlspecialchars($r['id']); ?></td>
              <td class="py-2"><?php echo htmlspecialchars($r['vendedor']); ?></td>
              <td class="py-2"><?php echo htmlspecialchars($r['direccion']); ?></td>
              <td class="py-2"><?php echo htmlspecialchars($r['fechaventa']); ?></td>
              <td class="py-2">
                <a href="show.php?id=<?php echo $r['id']; ?>" class="px-2 py-1 bg-green-700/20 rounded mr-2">Ver</a>
                <a href="edit.php?id=<?php echo $r['id']; ?>" class="px-2 py-1 bg-yellow-500/20 rounded mr-2">Editar</a>
                <a href="delete.php?id=<?php echo $r['id']; ?>" class="px-2 py-1 bg-red-600/20 rounded" onclick="return confirm('¿Eliminar?');">Eliminar</a>
              </td>
            </tr>
            <?php endwhile; else: ?>
            <tr>
              <td colspan="4" class="py-8 text-center text-green-300">No hay registros</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal para formulario -->
  <div id="newModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.7); z-index: 1050; align-items: center; justify-content: center;">
    <div style="background: #1f2937; border-radius: 12px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); max-width: 500px; width: 90%; padding: 2rem; max-height: 90vh; overflow-y: auto;">
      <div id="modalInner"></div>
    </div>
  </div>

  <link href="assets/css/style.css" rel="stylesheet">
  <script src="assets/js/app-tailwind.js"></script>
</body>
</html>