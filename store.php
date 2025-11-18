<?php
include "conexion.php";

// Evitar que mysqli lance excepciones no manejadas
mysqli_report(MYSQLI_REPORT_OFF);

// Detectar petición AJAX (fetch) para devolver JSON
$isAjax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    $isAjax = true;
} elseif (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
    $isAjax = true;
}

// Asegurar que se llame vía POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($isAjax) {
        header('Content-Type: application/json');
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    } else {
        header('Location: create.php');
    }
    exit;
}

// Validar que existe conexión
if (!isset($conexion) || !$conexion) {
    if ($isAjax) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos']);
    } else {
        echo "<p>Error: No hay conexión a la base de datos.</p><p><a href='create.php'>Volver</a></p>";
    }
    exit;
}

$vendedor = trim($_POST['vendedor'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$fechaventa = trim($_POST['fechaventa'] ?? '');

// Validación mínima
if ($vendedor === '' || $direccion === '' || $fechaventa === '') {
    if ($isAjax) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios']);
    } else {
        echo '<p>Todos los campos son obligatorios. <a href="create.php">Volver</a></p>';
    }
    exit;
}

// Forzar charset utf8mb4 para evitar problemas con caracteres
if (method_exists($conexion, 'set_charset')) {
    $conexion->set_charset('utf8mb4');
}

// Crear la tabla si no existe
$create_sql = "CREATE TABLE IF NOT EXISTS `ventas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendedor` VARCHAR(255) NOT NULL,
  `direccion` VARCHAR(255) NOT NULL,
  `fechaventa` DATE DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($conexion->query($create_sql) === false) {
    $msg = htmlspecialchars($conexion->error);
    if ($isAjax) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => "Error al crear tabla: $msg"]);
    } else {
        echo "<p>Error al crear la tabla: $msg</p>";
        echo '<p><a href="create.php">Volver al formulario</a></p>';
    }
    exit;
}

// Preparar e insertar
$stmt = $conexion->prepare("INSERT INTO ventas (vendedor, direccion, fechaventa) VALUES (?, ?, ?)");
if (!$stmt) {
    $err = htmlspecialchars($conexion->error);
    if ($isAjax) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $err]);
    } else {
        echo "<p>Error al preparar consulta: $err</p><p><a href='create.php'>Volver</a></p>";
    }
    exit;
}

if (!$stmt->bind_param('sss', $vendedor, $direccion, $fechaventa)) {
    $err = htmlspecialchars($stmt->error);
    $stmt->close();
    if ($isAjax) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $err]);
    } else {
        echo "<p>Error al enlazar parámetros: $err</p><p><a href='create.php'>Volver</a></p>";
    }
    exit;
}

if ($stmt->execute()) {
    $lastId = $conexion->insert_id;
    $stmt->close();
    
    if ($isAjax) {
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode(['success' => true, 'id' => $lastId]);
    } else {
        header('Location: index.php');
    }
    exit;
} else {
    $err = htmlspecialchars($stmt->error ?: $conexion->error);
    $stmt->close();
    
    if ($isAjax) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $err]);
    } else {
        echo "<p>Error al insertar: $err</p><p><a href='create.php'>Volver</a></p>";
    }
    exit;
}
?>

