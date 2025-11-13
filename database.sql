-- Crear la base de datos
CREATE DATABASE hotel_huanta;
USE hotel_huanta;

-- Tabla HOTEL (con tipos de habitacion y metodos de pago dentro)
CREATE TABLE hotel (
    id_hotel INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(150),
    telefono VARCHAR(20),
    tipos_habitacion VARCHAR(255), -- Ejemplo: 'Simple, Doble, Matrimonial, Suite'
    metodos_pago VARCHAR(255)      -- Ejemplo: 'Efectivo, Tarjeta, Transferencia'
);

-- Insertar los hoteles con tipos de habitación y métodos de pago
INSERT INTO hotel (nombre, direccion, telefono, tipos_habitacion, metodos_pago) VALUES
('Hotel Royal', 'Jr. Saenz Peña 5121 - Huanta', '931627566', 'Doble, Matrimonial', 'Efectivo, Tarjeta, Transferencia'),
('Hostal Gran imperial', 'Jr.Miguel Untiveros 257 - Huanta', '923056622', 'Simple, Suite', 'Efectivo, Tarjeta'),
('Hotel El Mirador', 'Jr. Ayacucho 234 - Huanta', '066-328654', 'Doble, Simple', 'Efectivo, Transferencia'),
('Hotel cas betalleluz', 'Av. Progreso 789 - Huanta', '066-327890', 'Matrimonial, Suite', 'Efectivo, Tarjeta'),
('Hotel Valencia', 'Jr. Libertad 123 - Huanta', '066-321456', 'Simple, Doble', 'Efectivo, Tarjeta, Transferencia'),
('Hostal paraiso', 'Av. Mariscal Cáceres 456 - Huanta', '066-325789', 'Doble, Matrimonial', 'Efectivo'),
('Hotel las vegas ', 'Jr. Ayacucho 234 - Huanta', '066-328654', 'Suite, Simple', 'Efectivo, Tarjeta'),
('Hospedaje Nina Quintana', 'Av. Progreso 789 - Huanta', '066-327890', 'Matrimonial, Doble', 'Efectivo, Transferencia'),
('Hotel Park suites', 'Plaza de Armas - Huanta', '066-324567', 'Suite, Matrimonial', 'Efectivo, Tarjeta'),
('Hotel morenos', 'Jr. Libertad 123 - Huanta', '066-321456', 'Simple, Doble, Suite', 'Efectivo, Tarjeta, Transferencia');
    
-- Tabla USUARIOS (igual que en tu estructura)
CREATE TABLE usuarios (
  id_usuario INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  nombre_completo VARCHAR(120) NOT NULL,
  rol ENUM('admin') DEFAULT 'admin',
  created_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  updated_at TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- Insertar admin
INSERT INTO usuarios (username, password, nombre_completo, rol) VALUES
('admin', '$2y$10$a4qsOmIrUcXN4ptudcU57uOQ7li/aLuuuRedYHOb1YoBnoRQsWgPi', 'Administrador General', 'admin');

-- Tabla de clientes que consumen la API
CREATE TABLE client_api (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ruc VARCHAR(20) NOT NULL,
    razon_social VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado TINYINT DEFAULT 1
);

-- Tabla de tokens asociados a cada cliente
CREATE TABLE tokens_api (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client_api INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado TINYINT DEFAULT 1,
    FOREIGN KEY (id_client_api) REFERENCES client_api(id)
);

-- Tabla para contar peticiones realizadas por token
CREATE TABLE count_request (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_token INT NOT NULL,
    tipo VARCHAR(50),
    fecha DATE,
    FOREIGN KEY (id_token) REFERENCES tokens_api(id)
);


 hotelesAPI
hotelesAPI/
│
├── config/
│   └── database.php
│
├── controllers/
│   ├── AuthController.php
│   └── HotelController.php
│
├── models/
│   ├── Usuario.php
│   └── Hotel.php
│
├── views/
│   ├── include/
│   │   ├── header.php
│   │   └── footer.php
│   ├── dashboard.php
│   ├── hoteles_list.php
│   └── hotel_form.php
│
├── public/
│   ├── index.php
│   ├── css/
│   └── js/
│
├── index.php
└── .htaccess

APIcelulares
-- ================================
