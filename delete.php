<?php
include "conexion.php";

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $stmt = $conexion->prepare("DELETE FROM ventas WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}
header('Location: index.php');
exit;
