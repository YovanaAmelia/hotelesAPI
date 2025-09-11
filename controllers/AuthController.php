<?php
// controllers/AuthController.php - Versión corregida
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login($username, $password) {
        $usuario = $this->usuarioModel->validarUsuario($username, $password);
        if ($usuario) {
            // Iniciar sesión si no está ya iniciada
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['user_id'] = $usuario['id_usuario'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
            $_SESSION['rol'] = $usuario['rol'];
            
            // Debug temporal
            error_log("Session created for user: " . $usuario['username']);
            error_log("Session data: " . print_r($_SESSION, true));
            
            return true;
        }
        return false;
    }

    public function logout() {
        // Iniciar sesión si no está ya iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Debug temporal
        error_log("Logout called");
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
        require_once __DIR__ . '/../config/database.php';
        
        // Redirigir al login
        header('Location: ' . BASE_URL . 'views/login.php?logout=1');
        exit();
    }
}

// Manejar peticiones directas al controlador
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $authController = new AuthController();
    $authController->logout();
}
?>