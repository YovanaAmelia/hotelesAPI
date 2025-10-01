<?php
require_once __DIR__ . '/../models/ClientApi.php';

class ClientApiController
{
    private $clientApiModel;

    public function __construct()
    {
        $this->clientApiModel = new ClientApi();
    }

    public function getConexion()
    {
        return $this->clientApiModel->getConexion();
    }

    // Listar todos los clientes
    public function listarClientes()
    {
        return $this->clientApiModel->obtenerClientes();
    }

    // Obtener un cliente por ID
    public function obtenerCliente($id)
    {
        return $this->clientApiModel->obtenerClientePorId($id);
    }

    // Crear un nuevo cliente (estado = 1 por defecto)
    public function crearCliente($ruc, $razon_social, $telefono, $correo)
    {
        $estado = 1; // Estado activo por defecto
        $stmt = $this->clientApiModel->getConexion()->prepare("INSERT INTO client_api (ruc, razon_social, telefono, correo, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $ruc, $razon_social, $telefono, $correo, $estado);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    // Editar un cliente
    public function editarCliente($id, $ruc, $razon_social, $telefono, $correo, $estado)
    {
        return $this->clientApiModel->actualizarCliente($id, $ruc, $razon_social, $telefono, $correo, $estado);
    }

    // Eliminar un cliente
    public function borrarCliente($id)
    {
        return $this->clientApiModel->eliminarCliente($id);
    }
}
?>
