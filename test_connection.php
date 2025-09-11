<?php
// Incluir el archivo de configuración de la base de datos
require_once __DIR__ . '/config/database.php';

// Función para probar la conexión a la base de datos
function testDatabaseConnection()
{
    try {
        $conexion = conectarDB();

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        } else {
            echo "<h2>Conexión exitosa a la base de datos: " . DB_NAME . "</h2>";

            // Probar una consulta simple
            $resultado = $conexion->query("SHOW TABLES");
            if ($resultado) {
                echo "<h3>Tabla de la base de datos:</h3>";
                echo "<ul>";
                while ($fila = $resultado->fetch_array()) {
                    echo "<li>" . $fila[0] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->error;
            }
        }
    } catch (Exception $e) {
        echo "Error al conectar a la base de datos: " . $e->getMessage();
    }
}

// Llamar a la función para probar la conexión
testDatabaseConnection();
?>
