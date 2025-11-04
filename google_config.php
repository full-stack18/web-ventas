<?php
// Incluir el autoloader de Composer
require_once 'vendor/autoload.php';

// Iniciar sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$google_client = new Google\Client();
$google_client->setClientId('155173029481-ae85drnin3ckhg9b4ogv6gs25t1021hi.apps.googleusercontent.com'); // REEMPLAZAR
$google_client->setClientSecret('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'); // REEMPLAZAR
$google_client->setRedirectUri('http://localhost/Web%20Ventas/google_callback.php'); // REEMPLAZAR con la URI de redirección configurada
$google_client->addScope('email');
$google_client->addScope('profile');

// Generar la URL de autenticación para el botón de login
$google_login_url = $google_client->createAuthUrl();

?>