<?php
// models/Usuario.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config/database.php';

class Usuario {
    private $conexion;

    public function __construct() {
        $this->conexion = conectarDB();
    }

    // Validar usuario (ya existe en tu código)
    public function validarUsuario($username, $password) {
        $stmt = $this->conexion->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    // Registrar un nuevo usuario
    public function registrarUsuario($username, $password, $nombre_completo, $rol = 'admin') {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conexion->prepare("INSERT INTO usuarios (username, password, nombre_completo, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashed_password, $nombre_completo, $rol);
        return $stmt->execute();
    }

    // Obtener todos los usuarios
    public function obtenerUsuarios() {
        $resultado = $this->conexion->query("SELECT * FROM usuarios");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener un usuario por ID
    public function obtenerUsuarioPorId($id_usuario) {
        $stmt = $this->conexion->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // Actualizar un usuario
    public function actualizarUsuario($id_usuario, $username, $nombre_completo, $rol) {
        $stmt = $this->conexion->prepare("UPDATE usuarios SET username = ?, nombre_completo = ?, rol = ? WHERE id_usuario = ?");
        $stmt->bind_param("sssi", $username, $nombre_completo, $rol, $id_usuario);
        return $stmt->execute();
    }

    // Eliminar un usuario
    public function eliminarUsuario($id_usuario) {
        $stmt = $this->conexion->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }
}
?>
