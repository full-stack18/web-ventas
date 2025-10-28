<?php
require 'conexionPDO.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "Tabla 'users' creada correctamente o ya existe.";
} catch(PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}
?>