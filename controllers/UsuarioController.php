<?php
// controllers/UsuarioController.php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    // Registrar un nuevo usuario
    public function registrar($username, $password, $nombre_completo, $rol = 'admin') {
        return $this->usuarioModel->registrarUsuario($username, $password, $nombre_completo, $rol);
    }

    // Obtener todos los usuarios
    public function listarUsuarios() {
        return $this->usuarioModel->obtenerUsuarios();
    }

    // Obtener un usuario por ID
    public function obtenerUsuario($id_usuario) {
        return $this->usuarioModel->obtenerUsuarioPorId($id_usuario);
    }

    // Actualizar un usuario
    public function actualizar($id_usuario, $username, $nombre_completo, $rol) {
        return $this->usuarioModel->actualizarUsuario($id_usuario, $username, $nombre_completo, $rol);
    }

    // Eliminar un usuario
    public function eliminar($id_usuario) {
        return $this->usuarioModel->eliminarUsuario($id_usuario);
    }
}
?>
