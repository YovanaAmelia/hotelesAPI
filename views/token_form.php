<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}
require_once __DIR__ . '/../controllers/TokenApiController.php';
$tokenApiController = new TokenApiController();

// Determinar si es edición o creación
$isEditing = isset($_GET['edit']) && is_numeric($_GET['edit']);
$token = null;
$pageTitle = $isEditing ? '✏️ Editar Token' : '➕ Generar Nuevo Token';

// Obtener clientes
$clientes = $tokenApiController->obtenerClientes();

if ($isEditing) {
    $token = $tokenApiController->obtenerToken($_GET['edit']);
    if (!$token) {
        header('Location: ' . BASE_URL . 'views/tokens_list.php');
        exit();
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_client_api = trim($_POST['id_client_api']);
    $estado = $isEditing ? (isset($_POST['estado']) ? 1 : 0) : 1;

    // Validaciones
    $errores = [];
    if (empty($id_client_api)) {
        $errores[] = "El campo Cliente es obligatorio";
    }

    if (empty($errores)) {
        if ($isEditing) {
            $resultado = $tokenApiController->editarToken($_GET['edit'], $estado);
            if ($resultado) {
                $mensaje = "✅ Token actualizado exitosamente";
                $tipo_mensaje = "success";
                $token = $tokenApiController->obtenerToken($_GET['edit']);
            } else {
                $mensaje = "❌ Error al actualizar el token";
                $tipo_mensaje = "error";
            }
        } else {
            $resultado = $tokenApiController->crearToken($id_client_api);
            if ($resultado) {
                header('Location: ' . BASE_URL . 'views/tokens_list.php?created=1');
                exit();
            } else {
                $mensaje = "❌ Error al generar el token";
                $tipo_mensaje = "error";
            }
        }
    }
}

require_once __DIR__ . '/include/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .dashboard-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        margin-top: 20px;
    }

    .card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        transition: transform 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card h3 {
        margin-top: 0;
        color: #2c3e50;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .card h3 i {
        margin-right: 10px;
        color: #3498db;
    }

    .card p {
        margin: 10px 0;
    }

    .card strong {
        color: #e74c3c;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #2c3e50;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    .quick-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .quick-actions a, .quick-actions button {
        padding: 10px 15px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-success {
        background: #27ae60;
        color: white;
    }

    .btn-warning {
        background: #f39c12;
        color: white;
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
    }

    .btn-cancel {
        background: #95a5a6;
        color: white;
    }

    .quick-actions a:hover, .quick-actions button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .required-note {
        color: #7f8c8d;
        font-size: 0.875rem;
    }

    .mensaje {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        display: flex;
        align-items: center;
    }

    .mensaje.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .mensaje.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .mensaje i {
        margin-right: 10px;
        font-size: 18px;
    }

    .nombre {
        display: block;
        text-align: center;
        font-size: 30px;
        font-weight: bold;
        unicode-bidi: isolate;
        margin-bottom: 20px;
    }

    .token-display {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        margin-top: 10px;
        font-family: monospace;
        word-break: break-all;
    }
</style>

<div class="dashboard-container">
    <h2 class="nombre"><i class="fas fa-key"></i> <?php echo $isEditing ? 'Editar Token' : 'Generar Nuevo Token'; ?></h2>

    <div class="card">
        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <i class="fas <?= $tipo_mensaje == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errores)): ?>
            <div class="mensaje error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>❌ Se encontraron los siguientes errores:</strong>
                    <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="id_client_api">Cliente *</label>
                <select id="id_client_api" name="id_client_api" class="form-control" required>
                    <option value="">-- Seleccione un cliente --</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['id']; ?>" <?php echo ($isEditing && $token['id_client_api'] == $cliente['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cliente['razon_social']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($isEditing): ?>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="estado" value="1" <?php echo isset($token['estado']) && $token['estado'] ? 'checked' : ''; ?>> Estado (Activo)
                    </label>
                </div>

                <div class="form-group">
                    <label>Token Generado</label>
                    <div class="token-display">
                        <?php echo htmlspecialchars($token['token']); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <div class="required-note">
                    <span>* Campos obligatorios</span>
                </div>
                <div class="quick-actions">
                    <a href="<?php echo BASE_URL; ?>views/tokens_list.php" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <?php if ($isEditing): ?>
                        <button type="submit" class="btn-warning">
                            <i class="fas fa-save"></i> Actualizar Token
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn-success">
                            <i class="fas fa-plus-circle"></i> Generar Token
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>
