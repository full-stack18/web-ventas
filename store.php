<?php
include "conexion.php";
// Evitar que mysqli lance excepciones no manejadas (asegura que usamos manejo manual)
mysqli_report(MYSQLI_REPORT_OFF);
try {
// Asegurar que se llame vía POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create.php');
    exit;
}

// Detectar petición AJAX (fetch) para devolver JSON
$isAjax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    $isAjax = true;
} elseif (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
    $isAjax = true;
}

$vendedor = trim($_POST['vendedor'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$fechaventa = trim($_POST['fechaventa'] ?? '');

// Forzar charset utf8mb4 para evitar problemas con caracteres
if (method_exists($conexion, 'set_charset')) {
    $conexion->set_charset('utf8mb4');
}

// Crear la tabla si no existe (ayuda a evitar errores en prepare cuando la tabla falta)
$create_sql = "CREATE TABLE IF NOT EXISTS `ventas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendedor` VARCHAR(255) NOT NULL,
  `direccion` VARCHAR(255) NOT NULL,
  `fechaventa` DATE DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($conexion->query($create_sql) === false) {
    $msg = htmlspecialchars($conexion->error);
    echo "<p>Error al crear la tabla: $msg</p>";
    echo '<p><a href="create.php">Volver al formulario</a></p>';
    exit;
}

// Verificar que la columna `id` exista y sea AUTO_INCREMENT
$colRes = $conexion->query("SHOW COLUMNS FROM `ventas` LIKE 'id'");
// No forzamos cambios estructurales en la base de datos aquí para evitar errores adicionales.
// Si la tabla existe con una columna `id` que no es AUTO_INCREMENT, utilizaremos un fallback al insertar
// (calcular MAX(id)+1 e insertar explícitamente ese id) para garantizar que las nuevas ventas se añadan.

// Validación mínima (cliente ya obliga required, pero server-side es recomendable)
if ($vendedor === '' || $direccion === '' || $fechaventa === '') {
    echo '<p>Todos los campos son obligatorios. <a href="create.php">Volver</a></p>';
    exit;
}

// Preparar e insertar
// Intento normal de inserción
$stmt = $conexion->prepare("INSERT INTO ventas (vendedor, direccion, fechaventa) VALUES (?, ?, ?)");
if ($stmt) {
    if ($stmt->bind_param('sss', $vendedor, $direccion, $fechaventa)) {
        try {
            $ok = $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            $ok = false;
            $execErr = $e->getMessage();
        }
        if (!empty($ok)) {
            $lastId = $conexion->insert_id;
            $stmt->close();
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'id' => $lastId]);
                exit;
            }
            header('Location: index.php');
            exit;
        }
        if (empty($execErr)) $execErr = $stmt->error;
    } else {
        $execErr = $stmt->error;
    }
    $stmt->close();
} else {
    $execErr = $conexion->error;
}

// Si la inserción falló por id no tener default (u otros problemas similares), intentamos fallback
$execErrLower = strtolower($execErr ?? '');
if (strpos($execErrLower, 'doesn\'t have a default value') !== false || strpos($execErrLower, 'no default value') !== false || strpos($execErrLower, 'cannot be null') !== false) {
    // Calcular next id
    $res = $conexion->query("SELECT MAX(id) as m FROM ventas");
    $nextId = 1;
    if ($res) {
        $row = $res->fetch_assoc();
        $max = intval($row['m'] ?? 0);
        $nextId = $max + 1;
    }

    // Intentar insertar con id explícito
    $stmt2 = $conexion->prepare("INSERT INTO ventas (id, vendedor, direccion, fechaventa) VALUES (?, ?, ?, ?)");
    if ($stmt2) {
        if ($stmt2->bind_param('isss', $nextId, $vendedor, $direccion, $fechaventa)) {
            try {
                $ok2 = $stmt2->execute();
            } catch (mysqli_sql_exception $e) {
                $ok2 = false;
                $execErr2 = $e->getMessage();
            }
            if (!empty($ok2)) {
                $lastId = $nextId;
                $stmt2->close();
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'id' => $lastId]);
                    exit;
                }
                header('Location: index.php');
                exit;
            } else {
                $err = htmlspecialchars($execErr2 ?? $stmt2->error ?: $conexion->error);
                $stmt2->close();
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => $err]);
                    exit;
                }
                echo "<p>Error al insertar con id manual: $err</p><p><a href=\"create.php\">Volver</a></p>";
                exit;
            }
        } else {
            $err = htmlspecialchars($stmt2->error ?: $conexion->error);
            $stmt2->close();
            echo "<p>Error al enlazar parámetros (fallback): $err</p><p><a href=\"create.php\">Volver</a></p>";
            exit;
        }
    } else {
        $err = htmlspecialchars($conexion->error);
        echo "<p>No se pudo preparar consulta fallback: $err</p><p><a href=\"create.php\">Volver</a></p>";
        exit;
    }
}

// Si llegamos aquí, hubo otro tipo de error
$errMsg = htmlspecialchars($execErr ?? $conexion->error ?? 'Error desconocido');
echo "<p>Error al ejecutar la inserción: $errMsg</p><p><a href=\"create.php\">Volver</a></p>";
exit;
} catch (Exception $e) {
    echo "<p>Excepción inesperada: " . htmlspecialchars($e->getMessage()) . "</p><p><a href=\"create.php\">Volver</a></p>";
    exit;
}
