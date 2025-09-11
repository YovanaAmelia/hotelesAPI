<?php
require_once __DIR__ . '/../models/Hotel.php';

class HotelController {
    private $hotelModel;

    public function __construct() {
        $this->hotelModel = new Hotel();
    }

    public function getConexion() {
        return $this->hotelModel->getConexion();
    }

    public function listarHoteles($limit = null, $offset = null) {
        if ($limit !== null && $offset !== null) {
            $query = "SELECT * FROM hotel LIMIT $limit OFFSET $offset";
            $resultado = $this->hotelModel->getConexion()->query($query);
            $hoteles = [];
            while ($fila = $resultado->fetch_assoc()) {
                $hoteles[] = $fila;
            }
            return $hoteles;
        } else {
            return $this->hotelModel->obtenerHoteles();
        }
    }

    public function obtenerHotel($id) {
        return $this->hotelModel->obtenerHotelPorId($id);
    }

    public function crearHotel($nombre, $direccion, $telefono, $tipos_habitacion, $metodos_pago) {
        return $this->hotelModel->guardarHotel($nombre, $direccion, $telefono, $tipos_habitacion, $metodos_pago);
    }

    public function editarHotel($id, $nombre, $direccion, $telefono, $tipos_habitacion, $metodos_pago) {
        return $this->hotelModel->actualizarHotel($id, $nombre, $direccion, $telefono, $tipos_habitacion, $metodos_pago);
    }

    public function borrarHotel($id) {
        return $this->hotelModel->eliminarHotel($id);
    }

    public function contarHoteles() {
        $resultado = $this->hotelModel->getConexion()->query("SELECT COUNT(*) as total FROM hotel");
        return $resultado->fetch_assoc()['total'];
    }
}
?>
