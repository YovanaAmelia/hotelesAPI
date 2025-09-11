<?php
// debug_login.php - Coloca este archivo en la raíz /APIDOCENTES/
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 DEBUG LOGIN - APIDOCENTES</h2>";
echo "<hr>";

// 1. Verificar configuración de base de datos
echo "<h3>1. Configuración de Base de Datos:</h3>";
require_once __DIR__ . '/config/database.php';

echo "DB_HOST: " . DB_HOST . "<br>";
echo "DB_NAME: " . DB_NAME . "<br>";
echo "DB_USER: " . DB_USER . "<br>";
echo "BASE_URL: " . BASE_URL . "<br>";

// 2. Probar conexión
echo "<h3>2. Prueba de Conexión:</h3>";
try {
    $conexion = conectarDB();
    echo "✅ Conexión exitosa<br>";
    
    // 3. Verificar datos del usuario admin
    echo "<h3>3. Datos del Usuario Admin:</h3>";
    $query = "SELECT id_usuario, username, password, nombre_completo FROM usuarios WHERE username = 'admin'";
    $resultado = $conexion->query($query);
    
    if ($resultado && $resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        echo "✅ Usuario encontrado:<br>";
        echo "ID: " . $usuario['id_usuario'] . "<br>";
        echo "Username: " . $usuario['username'] . "<br>";
        echo "Nombre: " . $usuario['nombre_completo'] . "<br>";
        echo "Hash actual: " . $usuario['password'] . "<br>";
        
        // 4. Probar password_verify
        echo "<h3>4. Prueba de Verificación de Contraseña:</h3>";
        $password_test = 'admin123';
        echo "Contraseña a probar: " . $password_test . "<br>";
        
        if (password_verify($password_test, $usuario['password'])) {
            echo "✅ La contraseña es CORRECTA<br>";
        } else {
            echo "❌ La contraseña es INCORRECTA<br>";
            
            // Generar nuevo hash
            echo "<h4>Generando nuevo hash:</h4>";
            $nuevo_hash = password_hash($password_test, PASSWORD_DEFAULT);
            echo "Nuevo hash: " . $nuevo_hash . "<br>";
            echo "<strong>Ejecuta esta consulta SQL:</strong><br>";
            echo "<code>UPDATE usuarios SET password = '$nuevo_hash' WHERE username = 'admin';</code><br>";
        }
    } else {
        echo "❌ Usuario 'admin' no encontrado<br>";
        echo "<h4>Usuarios en la tabla:</h4>";
        $all_users = $conexion->query("SELECT username FROM usuarios");
        while ($user = $all_users->fetch_assoc()) {
            echo "- " . $user['username'] . "<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
}

// 5. Probar el modelo Usuario
echo "<h3>5. Prueba del Modelo Usuario:</h3>";
try {
    require_once __DIR__ . '/models/Usuario.php';
    $usuarioModel = new Usuario();
    
    $resultado_login = $usuarioModel->validarUsuario('admin', 'admin123');
    if ($resultado_login) {
        echo "✅ El modelo Usuario funciona correctamente<br>";
        echo "Datos retornados: " . print_r($resultado_login, true) . "<br>";
    } else {
        echo "❌ El modelo Usuario no está funcionando<br>";
    }
} catch (Exception $e) {
    echo "❌ Error en modelo Usuario: " . $e->getMessage() . "<br>";
}

// 6. Información del servidor
echo "<h3>6. Información del Servidor:</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "HTTP Host: " . $_SERVER['HTTP_HOST'] . "<br>";

echo "<hr>";
echo "<h3>🎯 Próximos Pasos:</h3>";
echo "1. Si el hash es incorrecto, ejecuta la consulta SQL mostrada arriba<br>";
echo "2. Reemplaza tu .htaccess con la versión corregida<br>";
echo "3. Intenta hacer login nuevamente<br>";
echo "4. Si sigue fallando, revisa los logs de Apache/PHP<br>";
?>