<?php
// public/index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión al principio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/HotelController.php';

// Función para redirigir de forma segura
function safe_redirect($url) {
    if (ob_get_level()) {
        ob_end_clean();
    }

    header('Location: ' . $url);
    echo "<script>window.location.href = '$url';</script>";
    echo "<meta http-equiv='refresh' content='0;url=$url'>";
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        safe_redirect(BASE_URL . 'views/login.php?error=1');
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        safe_redirect(BASE_URL . 'views/login.php?error=1');
    }

    try {
        $authController = new AuthController();

        if ($authController->login($username, $password)) {
            safe_redirect(BASE_URL . 'views/dashboard.php');
        } else {
            safe_redirect(BASE_URL . 'views/login.php?error=1');
        }
    } catch (Exception $e) {
        safe_redirect(BASE_URL . 'views/login.php?error=1');
    }
} elseif ($action === 'logout') {
    session_unset();
    session_destroy();
    safe_redirect(BASE_URL . 'views/login.php');
} else {
    $authController = new AuthController();

    if ($authController->isAuthenticated()) {
        safe_redirect(BASE_URL . 'views/dashboard.php');
    } else {
        safe_redirect(BASE_URL . 'views/login.php');
    }
}

// Manejo de solicitudes API
$hotelController = new HotelController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'listar':
            header('Content-Type: application/json');
            echo json_encode($hotelController->listarHoteles());
            break;
        case 'obtener':
            if (isset($_GET['id'])) {
                header('Content-Type: application/json');
                echo json_encode($hotelController->obtenerHotel($_GET['id']));
            }
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'crear':
            $data = json_decode(file_get_contents('php://input'), true);
            $resultado = $hotelController->crearHotel(
                $data['nombre'],
                $data['direccion'],
                $data['telefono'],
                $data['tipos_habitacion'],
                $data['metodos_pago']
            );
            header('Content-Type: application/json');
            echo json_encode(['success' => $resultado]);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'editar':
            $data = json_decode(file_get_contents('php://input'), true);
            $resultado = $hotelController->editarHotel(
                $data['id_hotel'],
                $data['nombre'],
                $data['direccion'],
                $data['telefono'],
                $data['tipos_habitacion'],
                $data['metodos_pago']
            );
            header('Content-Type: application/json');
            echo json_encode(['success' => $resultado]);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'eliminar':
            $data = json_decode(file_get_contents('php://input'), true);
            $resultado = $hotelController->borrarHotel($data['id_hotel']);
            header('Content-Type: application/json');
            echo json_encode(['success' => $resultado]);
            break;
    }
}
?>
