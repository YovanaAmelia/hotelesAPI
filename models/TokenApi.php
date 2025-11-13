<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config/database.php';

class TokenApi
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = conectarDB();
    }

    public function getConexion()
    {
        return $this->conexion;
    }

    // Obtener todos los tokens
    public function obtenerTokens()
    {
        $query = "SELECT t.*, c.razon_social FROM tokens_api t JOIN client_api c ON t.id_client_api = c.id";
        $resultado = $this->conexion->query($query);
        $tokens = [];
        while ($fila = $resultado->fetch_assoc()) {
            $tokens[] = $fila;
        }
        return $tokens;
    }

    // Obtener un token por ID
    public function obtenerTokenPorId($id)
    {
        $stmt = $this->conexion->prepare("SELECT t.*, c.razon_social FROM tokens_api t JOIN client_api c ON t.id_client_api = c.id WHERE t.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // Obtener tokens por cliente
    public function obtenerTokensPorCliente($id_client_api)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM tokens_api WHERE id_client_api = ?");
        $stmt->bind_param("i", $id_client_api);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $tokens = [];
        while ($fila = $resultado->fetch_assoc()) {
            $tokens[] = $fila;
        }
        return $tokens;
    }

    // Guardar un nuevo token
    public function guardarToken($id_client_api, $token)
    {
        $stmt = $this->conexion->prepare("INSERT INTO tokens_api (id_client_api, token) VALUES (?, ?)");
        $stmt->bind_param("is", $id_client_api, $token);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    // Actualizar un token
    public function actualizarToken($id, $estado)
    {
        $stmt = $this->conexion->prepare("UPDATE tokens_api SET estado=? WHERE id=?");
        $stmt->bind_param("ii", $estado, $id);
        return $stmt->execute();
    }

    // Eliminar un token
    public function eliminarToken($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM tokens_api WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Generar un token aleatorio
   public function generarToken($id_client_api)
{
    $caracteresAleatorios = bin2hex(random_bytes(16)); // 32 caracteres hexadecimales
    $fechaRegistro = date('Ymd'); // Formato: AAAAMMDD
    $token = $caracteresAleatorios . '-' . $fechaRegistro . '-' . str_pad($id_client_api, 2, '0', STR_PAD_LEFT);
    return $token;
}


}
?>
