<?php
// logout.php - Coloca este archivo en la raíz /APIDOCENTES/
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión
session_start();

// Debug temporal
error_log("Logout accessed");
error_log("Session before logout: " . print_r($_SESSION, true));

// Destruir completamente la sesión
$_SESSION = array();

// Eliminar cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Incluir configuración para BASE_URL
require_once __DIR__ . '/config/database.php';

// Debug temporal
error_log("Session destroyed, redirecting to login");

// Redirigir al login
header('Location: ' . BASE_URL . 'views/login.php?logout=1');
exit();
?>