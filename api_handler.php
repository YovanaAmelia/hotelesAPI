<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/TokenApiController.php';
require_once __DIR__ . '/controllers/HotelController.php';

// Obtener el token y la acción
$token = $_POST['token'] ?? '';
$action = $_GET['action'] ?? '';

// Validar que el token no esté vacío
if (empty($token)) {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'Token no proporcionado.'
    ]);
    exit();
}

// Validar el token en la base de datos de HOTELESAPI
$tokenController = new TokenApiController();
$tokenData = $tokenController->obtenerTokenPorToken($token);

if (!$tokenData) {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'Token no encontrado en HOTELESAPI.'
    ]);
    exit();
}

if ($tokenData['estado'] != 1) {
    echo json_encode([
        'status' => false,
        'type' => 'warning',
        'msg' => 'Token inactivo en HOTELESAPI.'
    ]);
    exit();
}

// Procesar la acción
$hotelController = new HotelController();
switch ($action) {
    case 'buscarHoteles':
        $search = $_POST['search'] ?? '';
        $hoteles = $hotelController->buscarHoteles($search);
        echo json_encode([
            'status' => true,
            'type' => 'success',
            'data' => $hoteles
        ]);
        break;
    default:
        echo json_encode([
            'status' => false,
            'type' => 'error',
            'msg' => 'Acción no válida.'
        ]);
}
?>
