<?php
// views/usuarios_list.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/include/header.php';

$usuarioController = new UsuarioController();
$usuarios = $usuarioController->listarUsuarios();

if (isset($_GET['delete'])) {
    $usuarioController->eliminar($_GET['delete']);
    header('Location: ' . BASE_URL . 'views/usuarios_list.php');
    exit();
}
?>

<style>
    .container {
        margin: 20px;
        padding: 0;
    }

    .dashboard-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 25px;
        margin-top: 20px;
    }

    .list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .list-header h2 {
        color: #2c3e50;
        margin: 0;
        font-size: 24px;
    }

    .list-header h2 i {
        margin-right: 10px;
        color: #3498db;
    }

    .btn {
        padding: 10px 15px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
    }

    .btn i {
        margin-right: 8px;
    }

    .btn-success {
        background-color: #27ae60;
        color: white;
    }

    .btn-success:hover {
        background-color: #219653;
    }

    .table-container {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0;
    }

    .table th {
        background-color: #3498db;
        color: white;
        padding: 12px 15px;
        text-align: left;
        font-weight: 500;
    }

    .table th i {
        margin-right: 8px;
    }

    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        color: #333;
    }

    .table tr:hover {
        background-color: #f5f5f5;
    }

    .badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .role-badge {
        background-color: #e74c3c;
        color: white;
    }

    .table-actions {
        display: flex;
        gap: 10px;
    }

    .btn-warning {
        background-color: #f39c12;
        color: white;
    }

    .btn-warning:hover {
        background-color: #e67e22;
    }

    .btn-danger {
        background-color: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @media (max-width: 768px) {
        .list-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .table-actions {
            flex-direction: column;
            gap: 5px;
        }

        .btn {
            width: 100%;
            justify-content: flex-start;
        }
    }
</style>

<div class="container fade-in">
    <div class="dashboard-container">
        <div class="list-header">
            <h2>
                <i class="fas fa-users"></i> Lista de Usuarios
            </h2>
            <a href="<?= BASE_URL ?>views/usuario_form.php" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Agregar Usuario
            </a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-user"></i> Usuario</th>
                        <th><i class="fas fa-id-card"></i> Nombre Completo</th>
                        <th><i class="fas fa-user-tag"></i> Rol</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id_usuario'] ?></td>
                            <td><?= $usuario['username'] ?></td>
                            <td><?= $usuario['nombre_completo'] ?></td>
                            <td>
                                <span class="badge role-badge">
                                    <i class="fas fa-shield-alt"></i> <?= ucfirst($usuario['rol']) ?>
                                </span>
                            </td>
                            <td class="table-actions">
                                <a href="<?= BASE_URL ?>views/usuario_form.php?edit=<?= $usuario['id_usuario'] ?>" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="<?= BASE_URL ?>views/usuarios_list.php?delete=<?= $usuario['id_usuario'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
