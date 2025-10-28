<?php

$server = "localhost";
$user = "juan";
$password = "[duRPMs9Byr5yzbr"; 
$database = "ventas";

$conexion = new mysqli($server, $user, $password, $database);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}else{
     // Conexión exitosa (silenciada para no mostrar en cada página)
}

?>