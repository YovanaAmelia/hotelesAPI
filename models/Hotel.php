<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config/database.php';

class Hotel {
    private $conexion;

    public function __construct() {
        $this->conexion = conectarDB();
    }

    public function getConexion() {
        return $this->conexion;
    }

    public function obtenerHoteles() {
        $query = "SELECT * FROM hotel";
        $resultado = $this->conexion->query($query);
        $hoteles = [];
        while ($fila = $resultado->fetch_assoc()) {
            $hoteles[] = $fila;
        }
        return $hoteles;
    }

    public function obtenerHotelPorId($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM hotel WHERE id_hotel = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function guardarHotel($nombre, $direccion, $telefono, $tipos_habitacion, $metodos_pago) {
        $stmt = $this->conexion->prepare("INSERT INTO hotel (nombre, direccion, telefono, tipos_habitacion, metodos_pago) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $direccion, $telefono, $tipos_habitacion, $metodos_pago);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    public function actualizarHotel($id, $nombre, $direccion, $telefono, $tipos_habitacion, $metodos_pago) {
        $stmt = $this->conexion->prepare("UPDATE hotel SET nombre=?, direccion=?, telefono=?, tipos_habitacion=?, metodos_pago=? WHERE id_hotel=?");
        $stmt->bind_param("sssssi", $nombre, $direccion, $telefono, $tipos_habitacion, $metodos_pago, $id);
        return $stmt->execute();
    }

    public function eliminarHotel($id) {
        $stmt = $this->conexion->prepare("DELETE FROM hotel WHERE id_hotel=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
