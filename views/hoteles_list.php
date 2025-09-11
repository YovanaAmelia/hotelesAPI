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
    require_once __DIR__ . '/../controllers/HotelController.php';
    $hotelController = new HotelController();
    if ($hotelController->borrarHotel($_GET['delete'])) {
        $mensaje = "✅ Hotel eliminado exitosamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "❌ Error al eliminar el hotel";
        $tipo_mensaje = "error";
    }
}

// Obtener lista de hoteles
require_once __DIR__ . '/../controllers/HotelController.php';
$hotelController = new HotelController();

// Configuración de paginación
$hotelesPorPagina = 10;
$totalHoteles = $hotelController->contarHoteles();
$totalPaginas = ceil($totalHoteles / $hotelesPorPagina);
$paginaActual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($paginaActual - 1) * $hotelesPorPagina;

// Filtros de búsqueda
$filtroNombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$filtroTipoHabitacion = isset($_GET['tipo_habitacion']) ? trim($_GET['tipo_habitacion']) : '';

// Obtener todos los hoteles para aplicar los filtros
$todosLosHoteles = $hotelController->listarHoteles();

// Aplicar filtros
$hotelesFiltrados = [];
foreach ($todosLosHoteles as $hotel) {
    $coincideNombre = empty($filtroNombre) || stripos($hotel['nombre'], $filtroNombre) !== false;
    $coincideTipoHabitacion = empty($filtroTipoHabitacion) || stripos($hotel['tipos_habitacion'], $filtroTipoHabitacion) !== false;
    if ($coincideNombre && $coincideTipoHabitacion) {
        $hotelesFiltrados[] = $hotel;
    }
}

// Calcular paginación para los hoteles filtrados
$totalHotelesFiltrados = count($hotelesFiltrados);
$totalPaginasFiltradas = ceil($totalHotelesFiltrados / $hotelesPorPagina);

// Obtener los hoteles de la página actual después de aplicar los filtros
$hotelesPaginados = array_slice($hotelesFiltrados, $offset, $hotelesPorPagina);

require_once __DIR__ . '/include/header.php';
?>

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

    .filtros {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .filtros div {
        display: flex;
        flex-direction: column;
    }

    .filtros label {
        margin-bottom: 5px;
        font-weight: bold;
        color: #2c3e50;
    }

    .filtros input {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .filtros button {
        padding: 10px 15px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .filtros button:hover {
        background: #2980b9;
    }

    .filtros a {
        padding: 10px 15px;
        background: #e74c3c;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.3s;
    }

    .filtros a:hover {
        background: #c0392b;
    }

    #hotelesTable {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    #hotelesTable th, #hotelesTable td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    #hotelesTable th {
        background: #3498db;
        color: white;
    }

    #hotelesTable th i {
        margin-right: 8px;
    }

    #hotelesTable tr:hover {
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
</style>

<div class="contenedor">
    <?php if (isset($mensaje)): ?>
        <div class="mensaje <?= $tipo_mensaje; ?>">
            <i class="fas <?= $tipo_mensaje == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <div class="acciones">
        <h3><i class="fas fa-building"></i> Gestión de Hoteles</h3>
        <a href="<?php echo BASE_URL; ?>views/hotel_form.php"><i class="fas fa-plus-circle"></i> Agregar Nuevo Hotel</a>
    </div>

    <!-- Filtros de búsqueda -->
    <form method="GET" action="<?php echo BASE_URL; ?>views/hoteles_list.php" class="filtros">
        <input type="hidden" name="pagina" value="1">
        <div>
            <label for="nombre"><i class="fas fa-search"></i> Buscar por nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($filtroNombre); ?>" placeholder="Ingrese nombre del hotel">
        </div>
        <div>
            <label for="tipo_habitacion"><i class="fas fa-bed"></i> Buscar por tipo de habitación:</label>
            <input type="text" id="tipo_habitacion" name="tipo_habitacion" value="<?php echo htmlspecialchars($filtroTipoHabitacion); ?>" placeholder="Ej: Simple, Doble">
        </div>
        <button type="submit"><i class="fas fa-filter"></i> Buscar</button>
        <a href="<?php echo BASE_URL; ?>views/hoteles_list.php"><i class="fas fa-times"></i> Limpiar</a>
    </form>

    <?php if (empty($hotelesPaginados)): ?>
        <div class="mensaje error">
            <i class="fas fa-info-circle"></i> No se encontraron hoteles.
            <a href="<?php echo BASE_URL; ?>views/hotel_form.php" style="margin-left:10px; color:#27ae60;">
                <i class="fas fa-plus-circle"></i> Agregar Primer Hotel
            </a>
        </div>
    <?php else: ?>
        <table id="hotelesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th><i class="fas fa-hotel"></i> Nombre</th>
                    <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                    <th><i class="fas fa-phone"></i> Teléfono</th>
                    <th><i class="fas fa-bed"></i> Tipos de Habitación</th>
                    <th><i class="fas fa-credit-card"></i> Métodos de Pago</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $contador = $offset + 1; ?>
                <?php foreach ($hotelesPaginados as $hotel): ?>
                    <tr>
                        <td><?php echo $contador++; ?></td>
                        <td><?php echo htmlspecialchars($hotel['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($hotel['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($hotel['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($hotel['tipos_habitacion']); ?></td>
                        <td><?php echo htmlspecialchars($hotel['metodos_pago']); ?></td>
                        <td class="acciones-tabla">
                            <a href="<?php echo BASE_URL; ?>views/hotel_form.php?edit=<?php echo $hotel['id_hotel']; ?>">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="#" class="eliminar" onclick="confirmarEliminacion(<?php echo $hotel['id_hotel']; ?>, '<?php echo addslashes($hotel['nombre']); ?>', <?php echo $paginaActual; ?>, '<?php echo urlencode($filtroNombre); ?>', '<?php echo urlencode($filtroTipoHabitacion); ?>')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="paginacion">
            <?php if ($paginaActual > 1): ?>
                <a href="<?php echo BASE_URL; ?>views/hoteles_list.php?pagina=<?php echo $paginaActual - 1; ?><?php echo (!empty($filtroNombre) || !empty($filtroTipoHabitacion)) ? '&nombre=' . urlencode($filtroNombre) . '&tipo_habitacion=' . urlencode($filtroTipoHabitacion) : ''; ?>">
                    <i class="fas fa-arrow-left"></i> Anterior
                </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginasFiltradas; $i++): ?>
                <a href="<?php echo BASE_URL; ?>views/hoteles_list.php?pagina=<?php echo $i; ?><?php echo (!empty($filtroNombre) || !empty($filtroTipoHabitacion)) ? '&nombre=' . urlencode($filtroNombre) . '&tipo_habitacion=' . urlencode($filtroTipoHabitacion) : ''; ?>" class="<?= $i === $paginaActual ? 'activa' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginasFiltradas): ?>
                <a href="<?php echo BASE_URL; ?>views/hoteles_list.php?pagina=<?php echo $paginaActual + 1; ?><?php echo (!empty($filtroNombre) || !empty($filtroTipoHabitacion)) ? '&nombre=' . urlencode($filtroNombre) . '&tipo_habitacion=' . urlencode($filtroTipoHabitacion) : ''; ?>">
                    Siguiente <i class="fas fa-arrow-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function confirmarEliminacion(id, nombre, pagina, filtroNombre, filtroTipoHabitacion) {
        if (confirm(`¿Estás seguro de que deseas eliminar el hotel "${nombre}"?`)) {
            window.location.href = `<?php echo BASE_URL; ?>views/hoteles_list.php?delete=${id}&pagina=${pagina}&nombre=${filtroNombre}&tipo_habitacion=${filtroTipoHabitacion}`;
        }
    }
</script>

<?php require_once __DIR__ . '/include/footer.php'; ?>
