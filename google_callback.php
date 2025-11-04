<?php
// Incluir la configuración de Google
require 'google_config.php';

if (isset($_GET['code'])) {
    try {
        // 1. Intercambiar el código de autorización por un token de acceso
        $token = $google_client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if (isset($token['error'])) {
            // Manejo de error si Google no devuelve un token válido
            header('Location: login.php?error=google_auth_failed');
            exit;
        }

        $google_client->setAccessToken($token);
        
        // 2. Obtener la información del usuario
        $oauth2 = new Google\Service\Oauth2($google_client);
        $userInfo = $oauth2->userinfo->get();

        $email = $userInfo->email;
        $name = $userInfo->name;
        
        // 3. Crear la sesión de PHP y entrar al dashboard
        
        // Asignamos datos a la sesión
        $_SESSION["user_id"] = $userInfo->id; // Usamos el ID de Google como identificador
        $_SESSION["username"] = $name;       // Usamos el nombre del perfil como username
        $_SESSION["email"] = $email;         // Guardamos el email
        $_SESSION["logged_in_via"] = "google";
        
        // Redirigir al dashboard
        header("Location: /Web Ventas/src/dist/dashboard.php");
        exit;

    } catch (Exception $e) {
        // Manejar cualquier excepción
        error_log("Google Auth Error: " . $e->getMessage());
        header('Location: login.php?error=server_error');
        exit;
    }
} else {
    // Si se accede directamente sin código
    header('Location: login.php');
    exit;
}
?>