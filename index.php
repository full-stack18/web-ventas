<?php
$include_conn = include "conexion.php";
$allowedSort = ['id','fechaventa'];
$sortParam = $_GET['sort'] ?? 'id';
$sort = in_array($sortParam, $allowedSort) ? $sortParam : 'id';
$dirParam = $_GET['dir'] ?? 'asc';
$dir = (strtolower($dirParam) === 'desc') ? 'DESC' : 'ASC';
$perPage = intval($_GET['per_page'] ?? 10);
if ($perPage <= 0) $perPage = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

// Contar total para paginación
$countRes = $conexion->query("SELECT COUNT(*) as total FROM ventas");
$total = 0;
if ($countRes) {
	$rowc = $countRes->fetch_assoc();
	$total = intval($rowc['total'] ?? 0);
}

// Obtener registros con orden y límite
$perPage = max(1, $perPage);
$offset = max(0, $offset);
$sql = "SELECT * FROM ventas ORDER BY `$sort` $dir LIMIT $perPage OFFSET $offset";
$result = $conexion->query($sql);
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Listado de Ventas</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/style.css" rel="stylesheet">
	</head>
	<body>
		<div class="container py-5">
			<header class="d-flex justify-content-between align-items-center mb-4 app-header">
				<div class="brand">
					<div class="logo">V</div>
					<div>
						<div class="h5 mb-0">Web Ventas</div>
						<div class="small-muted">Panel de registro y gestión</div>
					</div>
				</div>
								<div class="d-flex align-items-center gap-3">
									<button id="openNewBtn" class="btn btn-soft-primary">Nueva venta</button>
									<!-- Simple link styled as button to the Tailwind index -->
									<a href="index-tailwind.php" class="btn btn-outline-primary" title="Abrir versión Tailwind" style="display:inline-flex;align-items:center;gap:8px;">
										<!-- Tailwind icon - REEMPLAZA EL SVG AQUÍ -->
										<img src="assets/images/Tailwind CSS.svg" alt="Tailwind" width="16" height="16" style="display:inline-block;">
										Tailwind
									</a>
									<!-- Dashboard button -->
									<a href="registrar.php" class="btn btn-soft-success" title="Ir a Registrar" style="display:inline-flex;align-items:center;gap:8px;">
										<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8z" stroke-linecap="round" stroke-linejoin="round"/></svg>
										Registrar
									</a>
									<!-- Theme toggle (static) -->
									<button id="themeToggleBtn" class="theme-toggle" title="Alternar tema" aria-label="Alternar modo oscuro"></button>
								</div>
			</header>

			<main class="card-modern bg-animated" style="position:relative;overflow:hidden;">
				<div class="accent-bubble bubble-1" aria-hidden="true"></div>
				<div class="accent-bubble bubble-2" aria-hidden="true"></div>
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h2 class="h5">Listado de ventas</h2>
					<div class="d-flex align-items-center gap-3">
						<div class="chip">Total: <?php echo ($result) ? $result->num_rows : 0; ?></div>
						<!-- Controles de orden a la derecha -->
						<div class="table-controls">
							<?php
							// helper para construir URL preservando GET
							function buildSortUrl($col, $dir) {
								$q = $_GET;
								$q['sort'] = $col;
								$q['dir'] = $dir;
								$q['page'] = 1; // volver a la página 1 al cambiar orden
								return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($q));
							}
							$currentSort = $_GET['sort'] ?? 'id';
							$currentDir = (isset($_GET['dir']) && strtolower($_GET['dir']) === 'desc') ? 'desc' : 'asc';
							// ID button
							$idNextDir = ($currentSort === 'id' && $currentDir === 'asc') ? 'desc' : 'asc';
							$fechaNextDir = ($currentSort === 'fechaventa' && $currentDir === 'asc') ? 'desc' : 'asc';
							$idArrow = ($currentSort === 'id') ? ($currentDir === 'asc' ? '▲' : '▼') : '';
							$fechaArrow = ($currentSort === 'fechaventa') ? ($currentDir === 'asc' ? '▲' : '▼') : '';
							?>
							<a href="<?php echo buildSortUrl('id', $idNextDir); ?>" class="btn btn-outline-primary btn-sm">ID <?php echo $idArrow; ?></a>
							<a href="<?php echo buildSortUrl('fechaventa', $fechaNextDir); ?>" class="btn btn-outline-primary btn-sm">Fecha <?php echo $fechaArrow; ?></a>
						</div>
						
						</div>
				</div>

								<!-- Modal container para cargar el fragmento de creación -->
								<div id="newModal" class="modal-backdrop-custom" aria-hidden="true">
									<div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
										<div id="modalInner"> <!-- contenido cargado dinámicamente -->
										</div>
									</div>
								</div>

																<!-- Fallback template: si la carga por fetch falla, JS puede inyectar este HTML dentro de #modalInner -->
																<template id="modalFormTemplate">
																	<?php include __DIR__ . '/create_fragment.php'; ?>
																</template>

				<div class="table-responsive">
					<table class="table table-modern w-100">
						<thead>
							<tr>
								<th>ID</th>
								<th>Vendedor</th>
								<th>Dirección</th>
								<th>Fecha</th>
								<th class="text-center">Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($result && $result->num_rows): ?>
								<?php while ($row = $result->fetch_assoc()): ?>
									<tr>
										<td><?php echo htmlspecialchars($row['id']); ?></td>
										<td><?php echo htmlspecialchars($row['vendedor']); ?></td>
										<td><?php echo htmlspecialchars($row['direccion']); ?></td>
										<td><?php echo htmlspecialchars($row['fechaventa']); ?></td>
										<td class="text-center card-actions">
											<a href="show.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info btn-sm-custom">Ver</a>
											<a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning btn-sm-custom">Editar</a>
											<a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger btn-sm-custom" onclick="return confirm('¿Eliminar registro?');">Eliminar</a>
										</td>
									</tr>
								<?php endwhile; ?>
								<?php else: ?>
								<tr>
									<td colspan="5" class="text-center">
										<div class="empty-illustration" aria-hidden="false">
											<div class="empty-left" aria-hidden="true"><?php include __DIR__ . '/assets/images/systems-left.svg'; ?></div>
											<div class="empty-right" aria-hidden="true"><?php include __DIR__ . '/assets/images/systems-right.svg'; ?></div>
											<div class="empty-center" aria-hidden="false"><?php include __DIR__ . '/assets/images/systems-center.svg'; ?></div>
											<div class="mt-3 small-muted">No hay ventas registradas aún — crea la primera venta.</div>
										</div>
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
										</table>
								</div>

								<!-- Paginación -->
								<nav class="mt-3" aria-label="Paginación">
									<?php
									$totalPages = max(1, (int) ceil($total / $perPage));
									$baseQuery = $_GET; unset($baseQuery['page']);
									?>
									<ul class="pagination">
										<?php for ($p = 1; $p <= $totalPages; $p++):
											$baseQuery['page'] = $p;
											$href = htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($baseQuery));
										?>
											<li class="page-item <?php echo $p === $page ? 'active' : ''; ?>"><a class="page-link" href="<?php echo $href; ?>"><?php echo $p; ?></a></li>
										<?php endfor; ?>
									</ul>
								</nav>
			</main>

			</div>
			<script src="assets/js/app.js"></script>
		</body>
	</html>