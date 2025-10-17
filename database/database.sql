-- Base de datos: appsalon

-- --------------------------------------------------------
-- Tabla: usuarios
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `nombre` VARCHAR(60) NOT NULL,
  `apellido` VARCHAR(60) NOT NULL,
  `email` VARCHAR(150) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `telefono` VARCHAR(20),
  `admin` TINYINT(1) DEFAULT 0,
  `confirmado` TINYINT(1) DEFAULT 0,
  `token` VARCHAR(30),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) 

-- --------------------------------------------------------
-- Tabla: servicios
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `nombre` VARCHAR(60) NOT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `descripcion` TEXT,
  `duracion` INT DEFAULT 60,
  `activo` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) 

-- --------------------------------------------------------
-- Tabla: citas
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citas` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `hora` TIME NOT NULL,
  `usuarioId` INT,
  `total` DECIMAL(10,2),
  `estado` ENUM('pendiente', 'confirmada', 'completada', 'cancelada') DEFAULT 'pendiente',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`usuarioId`) REFERENCES `users`(`id`) ON DELETE SET NULL
) 

-- --------------------------------------------------------
-- Tabla intermedia: citasServicios
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citasServicios` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `citaId` INT,
  `servicioId` INT,
  FOREIGN KEY (`citaId`) REFERENCES `citas`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`servicioId`) REFERENCES `services`(`id`) ON DELETE CASCADE
) 
