<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}

// Manejar eliminación
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    require_once __DIR__ . '/../controllers/TokenApiController.php';
    $tokenApiController = new TokenApiController();
    if ($tokenApiController->borrarToken($_GET['delete'])) {
        $mensaje = "✅ Token eliminado exitosamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "❌ Error al eliminar el token";
        $tipo_mensaje = "error";
    }
}

// Obtener lista de tokens
require_once __DIR__ . '/../controllers/TokenApiController.php';
$tokenApiController = new TokenApiController();
$tokens = $tokenApiController->listarTokens();

require_once __DIR__ . '/include/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .contenedor {
        margin-top: 20px;
    }

    .acciones {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .acciones h3 {
        margin: 0;
        color: #2c3e50;
    }

    .acciones a {
        background: #27ae60;
        color: white;
        padding: 10px 15px;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.3s;
    }

    .acciones a:hover {
        background: #219653;
    }

    .acciones a i {
        margin-right: 8px;
    }

    #tokensTable {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    #tokensTable th, #tokensTable td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    #tokensTable th {
        background: #3498db;
        color: white;
    }

    #tokensTable th i {
        margin-right: 8px;
    }

    #tokensTable tr:hover {
        background: #f5f5f5;
    }

    .acciones-tabla {
        display: flex;
        gap: 10px;
    }

    .acciones-tabla a {
        padding: 8px 12px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        transition: background 0.3s;
    }

    .acciones-tabla a:first-child {
        background: #f39c12;
    }

    .acciones-tabla a:first-child:hover {
        background: #e67e22;
    }

    .acciones-tabla a:last-child {
        background: #e74c3c;
    }

    .acciones-tabla a:last-child:hover {
        background: #c0392b;
    }

    .acciones-tabla a i {
        margin-right: 5px;
    }

    .paginacion {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .paginacion a {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s;
    }

    .paginacion a:hover {
        background: #3498db;
        color: white;
        border-color: #3498db;
    }

    .paginacion a.activa {
        background: #2c3e50;
        color: white;
        border-color: #2c3e50;
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

    .estado-activo {
        color: #27ae60;
        font-weight: bold;
    }

    .estado-inactivo {
        color: #e74c3c;
        font-weight: bold;
    }

    .empty-state {
        padding: 3rem;
        text-align: center;
        color: #666;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .token {
        font-family: monospace;
        font-size: 0.875rem;
        word-break: break-all;
    }
</style>

<div class="contenedor">
    <?php if (isset($mensaje)): ?>
        <div class="mensaje <?= $tipo_mensaje; ?>">
            <i class="fas <?= $tipo_mensaje == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <div class="acciones">
        <h3><i class="fas fa-key"></i> Gestión de Tokens</h3>
        <a href="<?php echo BASE_URL; ?>views/token_form.php"><i class="fas fa-plus-circle"></i> Generar Nuevo Token</a>
    </div>

    <?php if (empty($tokens)): ?>
        <div class="mensaje error">
            <i class="fas fa-info-circle"></i> No se encontraron tokens.
            <a href="<?php echo BASE_URL; ?>views/token_form.php" style="margin-left:10px; color:#27ae60;">
                <i class="fas fa-plus-circle"></i> Generar Primer Token
            </a>
        </div>
    <?php else: ?>
        <table id="tokensTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th><i class="fas fa-building"></i> Cliente</th>
                    <th><i class="fas fa-key"></i> Token</th>
                    <th><i class="fas fa-calendar"></i> Fecha de Registro</th>
                    <th><i class="fas fa-toggle-on"></i> Estado</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $contador = 1; ?>
                <?php foreach ($tokens as $token): ?>
                    <tr>
                        <td><?php echo $contador++; ?></td>
                        <td><?php echo htmlspecialchars($token['razon_social']); ?></td>
                        <td class="token"><?php echo htmlspecialchars($token['token']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($token['fecha_registro'])); ?></td>
                        <td class="<?php echo $token['estado'] ? 'estado-activo' : 'estado-inactivo'; ?>">
                            <?php echo $token['estado'] ? 'Activo' : 'Inactivo'; ?>
                        </td>
                        <td class="acciones-tabla">
                            <a href="<?php echo BASE_URL; ?>views/token_form.php?edit=<?php echo $token['id']; ?>">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="#" class="eliminar" onclick="confirmarEliminacion(<?php echo $token['id']; ?>, '<?php echo addslashes($token['token']); ?>')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
    function confirmarEliminacion(id, token) {
        if (confirm(`¿Estás seguro de que deseas eliminar el token "${token.substring(0, 10)}..."?`)) {
            window.location.href = `<?php echo BASE_URL; ?>views/tokens_list.php?delete=${id}`;
        }
    }
</script>
