<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config/database.php';

class ClientApi
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

    // Obtener todos los clientes
    public function obtenerClientes()
    {
        $query = "SELECT * FROM client_api";
        $resultado = $this->conexion->query($query);
        $clientes = [];
        while ($fila = $resultado->fetch_assoc()) {
            $clientes[] = $fila;
        }
        return $clientes;
    }

    // Obtener un cliente por ID
    public function obtenerClientePorId($id)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM client_api WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // Guardar un nuevo cliente
    public function guardarCliente($ruc, $razon_social, $telefono, $correo)
    {
        $stmt = $this->conexion->prepare("INSERT INTO client_api (ruc, razon_social, telefono, correo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $ruc, $razon_social, $telefono, $correo);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    // Actualizar un cliente
    public function actualizarCliente($id, $ruc, $razon_social, $telefono, $correo, $estado)
    {
        $stmt = $this->conexion->prepare("UPDATE client_api SET ruc=?, razon_social=?, telefono=?, correo=?, estado=? WHERE id=?");
        $stmt->bind_param("ssssii", $ruc, $razon_social, $telefono, $correo, $estado, $id);
        return $stmt->execute();
    }

    // Eliminar un cliente
    public function eliminarCliente($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM client_api WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>