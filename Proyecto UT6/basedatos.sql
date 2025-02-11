-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS valoracion_productos;
USE valoracion_productos;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(20) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(100) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de votos
CREATE TABLE IF NOT EXISTS votos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT DEFAULT 0,
    idPr INT NOT NULL,
    idUs VARCHAR(20) NOT NULL,
    CONSTRAINT fk_votos_usu FOREIGN KEY(idUs) REFERENCES usuarios(usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_votos_pro FOREIGN KEY(idPr) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Datos iniciales para pruebas
INSERT INTO usuarios (usuario, contrasena) 
VALUES 
('admin', 'admin123'),
('ana', 'ana123');

INSERT INTO productos (nombre_producto) 
VALUES 
('Producto A'),
('Producto B');