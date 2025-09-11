<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}

require_once __DIR__ . '/../controllers/HotelController.php';
$hotelController = new HotelController();

// Determinar si es edición o creación
$isEditing = isset($_GET['edit']) && is_numeric($_GET['edit']);
$hotel = null;
$pageTitle = $isEditing ? 'Editar Hotel' : 'Agregar Nuevo Hotel';

if ($isEditing) {
    $hotel = $hotelController->obtenerHotel($_GET['edit']);
    if (!$hotel) {
        header('Location: ' . BASE_URL . 'views/hoteles_list.php');
        exit();
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);
    $tipos_habitacion = isset($_POST['tipos_habitacion']) ? implode(", ", $_POST['tipos_habitacion']) : '';
    $nuevos_tipos_habitacion = trim($_POST['nuevos_tipos_habitacion']);
    if (!empty($nuevos_tipos_habitacion)) {
        $tipos_habitacion .= ($tipos_habitacion ? ", " : "") . $nuevos_tipos_habitacion;
    }
    $metodos_pago = isset($_POST['metodos_pago']) ? implode(", ", $_POST['metodos_pago']) : '';
    $nuevos_metodos_pago = trim($_POST['nuevos_metodos_pago']);
    if (!empty($nuevos_metodos_pago)) {
        $metodos_pago .= ($metodos_pago ? ", " : "") . $nuevos_metodos_pago;
    }

    // Validaciones
    $errores = [];
    if (empty($nombre)) $errores[] = "El campo Nombre es obligatorio";
    if (empty($direccion)) $errores[] = "El campo Dirección es obligatorio";

    if (empty($errores)) {
        if ($isEditing) {
            $resultado = $hotelController->editarHotel(
                $_GET['edit'],
                $nombre,
                $direccion,
                $telefono,
                $tipos_habitacion,
                $metodos_pago
            );
            $mensaje = $resultado ? "✅ Hotel actualizado exitosamente" : "❌ Error al actualizar el hotel";
            $tipo_mensaje = $resultado ? "success" : "error";
            $hotel = $hotelController->obtenerHotel($_GET['edit']);
        } else {
            $resultado = $hotelController->crearHotel(
                $nombre,
                $direccion,
                $telefono,
                $tipos_habitacion,
                $metodos_pago
            );
            if ($resultado) {
                header('Location: ' . BASE_URL . 'views/hoteles_list.php?created=1');
                exit();
            } else {
                $mensaje = "❌ Error al crear el hotel";
                $tipo_mensaje = "error";
            }
        }
    }
}

// Lista de tipos de habitación y métodos de pago comunes
$tiposHabitacionComunes = ["Simple", "Doble", "Matrimonial", "Suite"];
$metodosPagoComunes = ["Efectivo", "Tarjeta", "Transferencia"];

require_once __DIR__ . '/include/header.php';
?>

<style>
    .form-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
        margin-top: 0;
        color: #2c3e50;
    }

    .form-container h2 i {
        margin-right: 10px;
        color: #3498db;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .form-actions a {
        background: #95a5a6;
        color: white;
        padding: 10px 15px;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.3s;
    }

    .form-actions a:hover {
        background: #7f8c8d;
    }

    .form-actions a i {
        margin-right: 8px;
    }

    .alert-success, .alert-error {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #2c3e50;
    }

    .form-group label i {
        margin-right: 8px;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    .form-group textarea {
        height: 100px;
        resize: vertical;
    }

    .checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        margin: 10px 0;
    }

    .checkbox-group div {
        display: flex;
        align-items: center;
    }

    .checkbox-group input[type="checkbox"] {
        margin-right: 8px;
    }

    .form-actions-bottom {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn {
        padding: 10px 15px;
        border-radius: 4px;
        text-decoration: none;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s;
    }

    .btn-secondary {
        background: #95a5a6;
        color: white;
        border: 1px solid #95a5a6;
    }

    .btn-secondary:hover {
        background: #7f8c8d;
        border-color: #7f8c8d;
    }

    .btn-primary {
        background: #27ae60;
        color: white;
        border: 1px solid #27ae60;
    }

    .btn-primary:hover {
        background: #219653;
        border-color: #219653;
    }

    .btn i {
        margin-right: 8px;
    }
</style>

<div class="form-container">
    <div class="form-actions">
        <h2><i class="fas fa-hotel"></i> <?php echo $pageTitle; ?></h2>
        <a href="<?php echo BASE_URL; ?>views/hoteles_list.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
    </div>

    <?php if (isset($mensaje)): ?>
        <div class="alert-<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="alert-error">
            <strong>⚠ Se encontraron errores:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nombre"><i class="fas fa-signature"></i> Nombre *</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($hotel['nombre'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección *</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($hotel['direccion'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($hotel['telefono'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label><i class="fas fa-bed"></i> Tipos de Habitación</label>
            <div class="checkbox-group">
                <?php foreach ($tiposHabitacionComunes as $tipo): ?>
                    <div>
                        <input type="checkbox" id="tipo_<?php echo strtolower($tipo); ?>" name="tipos_habitacion[]" value="<?php echo $tipo; ?>" <?php echo ($hotel && strpos($hotel['tipos_habitacion'], $tipo) !== false) ? 'checked' : ''; ?>>
                        <label for="tipo_<?php echo strtolower($tipo); ?>"><?php echo $tipo; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="text" id="nuevos_tipos_habitacion" name="nuevos_tipos_habitacion" placeholder="Otros tipos de habitación">
        </div>

        <div class="form-group">
            <label><i class="fas fa-credit-card"></i> Métodos de Pago</label>
            <div class="checkbox-group">
                <?php foreach ($metodosPagoComunes as $metodo): ?>
                    <div>
                        <input type="checkbox" id="metodo_<?php echo strtolower($metodo); ?>" name="metodos_pago[]" value="<?php echo $metodo; ?>" <?php echo ($hotel && strpos($hotel['metodos_pago'], $metodo) !== false) ? 'checked' : ''; ?>>
                        <label for="metodo_<?php echo strtolower($metodo); ?>"><?php echo $metodo; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="text" id="nuevos_metodos_pago" name="nuevos_metodos_pago" placeholder="Otros métodos de pago">
        </div>

        <div class="form-actions-bottom">
            <a href="<?php echo BASE_URL; ?>views/hoteles_list.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo $isEditing ? 'Actualizar Hotel' : 'Crear Hotel'; ?></button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
