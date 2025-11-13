<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'hotel_huanta');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de rutas base
define('BASE_URL', 'http://localhost/hotelesAPI/');

define('BASE_PATH', __DIR__ . '/../');

// Función para conectar a la base de datos
function conectarDB() {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    $conexion->set_charset(DB_CHARSET);
    return $conexion;
}
?>
