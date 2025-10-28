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
<body class="bg-gray-900 text-green-400 min-h-screen">
  <div class="container mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-green-400 rounded-lg flex items-center justify-center font-bold text-black">V</div>
        <div>
          <h1 class="text-2xl font-semibold">Web Ventas (Tailwind)</h1>
          <p class="text-sm text-green-300">Versión alternativa con Tailwind</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
          <a href="create.php" class="px-3 py-2 bg-green-600 text-black rounded">Nueva venta</a>
          <a href="index.php" class="px-3 py-2 bg-green-600 text-black rounded">Volver clásico</a>
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
</body>
</html>