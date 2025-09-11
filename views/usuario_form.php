<?php
// views/usuario_form.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/include/header.php';

$usuarioController = new UsuarioController();
$usuario = null;
$action = "registrar";

if (isset($_GET['edit'])) {
    $action = "actualizar";
    $usuario = $usuarioController->obtenerUsuario($_GET['edit']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === "registrar") {
        $usuarioController->registrar($_POST['username'], $_POST['password'], $_POST['nombre_completo'], $_POST['rol']);
    } else {
        $usuarioController->actualizar($_POST['id_usuario'], $_POST['username'], $_POST['nombre_completo'], $_POST['rol']);
    }
    header('Location: ' . BASE_URL . 'views/usuarios_list.php');
    exit();
}
?>

<style>
    .form-container {
        background: white;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
        margin-top: 20px;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #2d3748;
    }

    .form-input {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        transition: border 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-success {
        background-color: #48bb78;
        color: white;
    }

    .btn-success:hover {
        background-color: #38a169;
    }

    .btn-back {
        background-color: #e2e8f0;
        color: #4a5568;
    }

    .btn-back:hover {
        background-color: #cbd5e0;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }
</style>

<div class="container fade-in">
    <div class="dashboard-container">
        <div class="form-container">
            <div class="form-header">
                <h2 style="color: #2d3748; margin: 0;">
                    <i class="fas fa-user-shield"></i> <?= ucfirst($action) ?> Usuario
                </h2>
            </div>
            <form method="POST">
                <?php if ($action === "actualizar" && $usuario): ?>
                    <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Nombre de Usuario:</label>
                    <input type="text" id="username" name="username" class="form-input" value="<?= $usuario ? $usuario['username'] : '' ?>" required>
                </div>
                <?php if ($action === "registrar"): ?>
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-input" required>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="nombre_completo"><i class="fas fa-id-card"></i> Nombre Completo:</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" class="form-input" value="<?= $usuario ? $usuario['nombre_completo'] : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="rol"><i class="fas fa-user-tag"></i> Rol:</label>
                    <select id="rol" name="rol" class="form-input" required>
                        <option value="admin" <?= ($usuario && $usuario['rol'] === 'admin') ? 'selected' : '' ?>>Administrador</option>
                    </select>
                </div>
                <div class="form-actions">
                    <a href="<?= BASE_URL ?>views/usuarios_list.php" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> <?= ucfirst($action) ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
