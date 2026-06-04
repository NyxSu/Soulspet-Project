-- ============================================================
-- SoulsPets - Script de base de datos
-- ¿Cómo usarlo?
--   1. Abre phpMyAdmin en http://localhost/phpmyadmin
--   2. Clic en "Nueva" base de datos → nombre: soulspet
--   3. Seleccionarla → pestaña "Importar" → subir este archivo
--   4. Clic en "Continuar"
-- ============================================================

CREATE DATABASE IF NOT EXISTS soulspet
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE soulspet;

-- ============================================================
-- Tabla: usuarios
-- ============================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(100)  NOT NULL,
    email        VARCHAR(150)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol          ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Tabla: mascotas
-- ============================================================
CREATE TABLE IF NOT EXISTS mascotas (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id     INT NOT NULL,
    nombre_mascota VARCHAR(100) NOT NULL,
    tipo           ENUM('perro','gato') NOT NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Tabla: servicios
-- ============================================================
CREATE TABLE IF NOT EXISTS servicios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)   NOT NULL,
    descripcion TEXT,
    precio      DECIMAL(8,2)   NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Tabla: reservas
-- ============================================================
CREATE TABLE IF NOT EXISTS reservas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT NOT NULL,
    mascota_id  INT NOT NULL,
    servicio_id INT NOT NULL,
    fecha       DATE NOT NULL,
    hora        TIME NOT NULL,
    estado      ENUM('pendiente','confirmada','completada','cancelada') NOT NULL DEFAULT 'pendiente',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id)  REFERENCES usuarios(id)  ON DELETE CASCADE,
    FOREIGN KEY (mascota_id)  REFERENCES mascotas(id)  ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Admin inicial
-- Email    : admin@soulspet.com
-- Password : Admin123
--
-- IMPORTANTE: Este hash es provisional. Después de importar
-- este SQL debes visitar http://localhost/soulspet/setup.php
-- para que PHP regenere el hash de forma nativa y el login
-- funcione correctamente.
-- ============================================================
INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES
('Administrador', 'admin@soulspet.com',
 '$2y$10$Ry0sYwSnRE8eH7ijPBtieuRqeGVFGP91rZq82RT61TBiHkD3yB6eq',
 'admin');

-- ============================================================
-- Servicios iniciales
-- ============================================================
INSERT INTO servicios (nombre, descripcion, precio) VALUES
('Baño Completo',
 'Baño con shampoo especializado, secado profesional y perfumado suave para tu mascota.',
 35.00),
('Corte de Uñas',
 'Corte y limado de uñas con técnica profesional, sin estrés para tu compañero.',
 15.00),
('Cepillado y Peinado',
 'Cepillado profundo, desenredo y peinado adaptado al tipo de pelaje de tu mascota.',
 25.00),
('Corte de Pelo',
 'Corte estético completo, adaptado a la raza y preferencia del dueño.',
 50.00),
('Cuidado por el Día',
 'Guardería diurna con paseos, juegos y atención personalizada de 8am a 6pm.',
 60.00);
