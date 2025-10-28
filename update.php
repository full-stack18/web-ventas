<?php
include "conexion.php";

// Asegurar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = intval($_POST['id'] ?? 0);
$vendedor = trim($_POST['vendedor'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$fechaventa = trim($_POST['fechaventa'] ?? '');

if ($id <= 0) {
    echo '<p>ID inválido. <a href="index.php">Volver</a></p>';
    exit;
}

// Forzar charset
if (method_exists($conexion, 'set_charset')) {
    $conexion->set_charset('utf8mb4');
}

// Validación mínima
if ($vendedor === '' || $direccion === '' || $fechaventa === '') {
    echo '<p>Todos los campos son obligatorios. <a href="edit.php?id=' . intval($id) . '">Volver</a></p>';
    exit;
}

// Preparar la actualización y comprobar errores
$stmt = $conexion->prepare("UPDATE ventas SET vendedor = ?, direccion = ?, fechaventa = ? WHERE id = ?");
if (!$stmt) {
    $err = htmlspecialchars($conexion->error);
    echo "<p>Error en la preparación de la consulta: $err</p><p><a href=\"edit.php?id=$id\">Volver</a></p>";
    exit;
}

if (!$stmt->bind_param('sssi', $vendedor, $direccion, $fechaventa, $id)) {
    $err = htmlspecialchars($stmt->error);
    $stmt->close();
    echo "<p>Error al enlazar parámetros: $err</p><p><a href=\"edit.php?id=$id\">Volver</a></p>";
    exit;
}

if (!$stmt->execute()) {
    $err = htmlspecialchars($stmt->error ?: $conexion->error);
    $stmt->close();
    echo "<p>Error al ejecutar la actualización: $err</p><p><a href=\"edit.php?id=$id\">Volver</a></p>";
    exit;
}

$stmt->close();
header('Location: index.php');
exit;

