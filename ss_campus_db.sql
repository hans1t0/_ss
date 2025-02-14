# ************************************************************
# Sequel Ace SQL dump
# Versión 20080
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Equipo: 192.168.2.87 (MySQL 5.5.5-10.9.8-MariaDB-1:10.9.8+maria~ubu2204)
# Base de datos: ss_campus_db
# Tiempo de generación: 2025-01-30 09:25:44 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Volcado de tabla consentimientos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `consentimientos`;

CREATE TABLE `consentimientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `padre_id` int(11) NOT NULL,
  `tipo` enum('datos','imagen') NOT NULL,
  `aceptado` tinyint(1) DEFAULT 1,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `version_documento` varchar(10) DEFAULT '1.0',
  `ip_aceptacion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `padre_id` (`padre_id`),
  CONSTRAINT `consentimientos_ibfk_1` FOREIGN KEY (`padre_id`) REFERENCES `padres` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `consentimientos` WRITE;
/*!40000 ALTER TABLE `consentimientos` DISABLE KEYS */;

INSERT INTO `consentimientos` (`id`, `padre_id`, `tipo`, `aceptado`, `fecha`)
VALUES
	(1,2,'datos',1,'2025-01-27 11:44:42'),
	(2,3,'datos',1,'2025-01-27 12:39:50'),
	(3,5,'datos',1,'2025-01-27 18:33:44'),
	(4,6,'datos',1,'2025-01-27 18:44:53'),
	(5,7,'datos',1,'2025-01-27 18:53:14');

/*!40000 ALTER TABLE `consentimientos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla descuentos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `descuentos`;

CREATE TABLE `descuentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jugador_id` int(11) NOT NULL,
  `descuento` decimal(5,2) NOT NULL,
  `tiene_hermanos` tinyint(1) NOT NULL DEFAULT 0,
  `tipo_descuento` enum('hermano','club','especial') DEFAULT 'hermano',
  `comentario` varchar(255) DEFAULT NULL,
  `fecha_aplicacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `jugador_id` (`jugador_id`),
  CONSTRAINT `descuentos_ibfk_1` FOREIGN KEY (`jugador_id`) REFERENCES `jugadores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `descuentos` WRITE;
/*!40000 ALTER TABLE `descuentos` DISABLE KEYS */;

INSERT INTO `descuentos` (`id`, `jugador_id`, `descuento`, `tiene_hermanos`)
VALUES
	(1,1,0.00,0),
	(2,2,5.00,1),
	(3,3,0.00,0),
	(4,5,0.00,0),
	(5,6,5.00,1),
	(6,7,10.00,1),
	(7,8,0.00,0),
	(8,9,0.00,0);

/*!40000 ALTER TABLE `descuentos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla jugadores
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jugadores`;

CREATE TABLE `jugadores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `padre_id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL CHECK (fecha_nacimiento > '2000-01-01'),
  `sexo` enum('H','M') NOT NULL,
  `grupo` enum('Querubin','Prebenjamin','Benjamin','Alevin') NOT NULL,
  `modalidad` enum('RPSJ','NO_RPSJ') NOT NULL,
  `demarcacion` enum('jugador','portero') NOT NULL,
  `lesiones` text DEFAULT NULL,
  `jugador_numero` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `padre_id` (`padre_id`),
  KEY `idx_jugador_grupo` (`grupo`),
  KEY `idx_jugador_modalidad` (`modalidad`),
  ADD INDEX `idx_jugador_fecha_nacimiento` (`fecha_nacimiento`),
  ADD INDEX `idx_jugador_sexo` (`sexo`),
  ADD FULLTEXT INDEX `idx_jugador_nombre` (`nombre_completo`),
  CONSTRAINT `chk_jugador_edad` CHECK (YEAR(CURDATE()) - YEAR(fecha_nacimiento) <= 18),
  CONSTRAINT `jugadores_ibfk_1` FOREIGN KEY (`padre_id`) REFERENCES `padres` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `jugadores` WRITE;
/*!40000 ALTER TABLE `jugadores` DISABLE KEYS */;

INSERT INTO `jugadores` (`id`, `padre_id`, `nombre_completo`, `fecha_nacimiento`, `sexo`, `grupo`, `modalidad`, `demarcacion`, `lesiones`, `jugador_numero`)
VALUES
	(1,2,'Lucas Cok','2015-01-16','H','Benjamin','RPSJ','jugador','nada',1),
	(2,2,'Mia Cok','2019-12-12','M','Querubin','RPSJ','portero','naha',2),
	(3,3,'Luca Siro','2025-01-16','H','Alevin','RPSJ','jugador','',0),
	(5,5,'Abel','2024-12-30','H','Querubin','RPSJ','jugador','',1),
	(6,5,'Cain','2024-12-30','H','Querubin','RPSJ','jugador','',2),
	(7,5,'Zeus','2024-12-31','H','Querubin','RPSJ','jugador','',3),
	(8,6,'Mercurio','2025-01-06','H','Alevin','NO_RPSJ','portero','',1),
	(9,7,'Antonio Ruedas','2003-12-12','H','Alevin','NO_RPSJ','portero','',1);

/*!40000 ALTER TABLE `jugadores` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla padres
# ------------------------------------------------------------

DROP TABLE IF EXISTS `padres`;

CREATE TABLE `padres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `email` varchar(100) NOT NULL,
  `metodo_pago` enum('transferencia','coordinador') NOT NULL,
  `cuenta_bancaria` varchar(24) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ip_registro` varchar(45) DEFAULT NULL,
  `estado` enum('activo','inactivo','pendiente') NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `idx_padre_dni` (`dni`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `padres` WRITE;
/*!40000 ALTER TABLE `padres` DISABLE KEYS */;

INSERT INTO `padres` (`id`, `nombre`, `dni`, `telefono`, `email`, `metodo_pago`, `cuenta_bancaria`, `fecha_registro`)
VALUES
	(2,'Hans Cok','12345678Z','677283758','hans1to@me.com','coordinador',NULL,'2025-01-27 11:44:42'),
	(3,'Gema maria','12345678Z','647729651','gema@a.es','transferencia',NULL,'2025-01-27 12:39:50'),
	(5,'Pepe Pardo','12345678C','677283758','hans1to@me.com','transferencia',NULL,'2025-01-27 18:33:44'),
	(6,'Pepe Pardo2','12345678C','677283758','hans1to@me.com','transferencia',NULL,'2025-01-27 18:44:53'),
	(7,'Antonio Ruedas','12345678X','647729651','hans1to@me.com','coordinador',NULL,'2025-01-27 18:53:14');

/*!40000 ALTER TABLE `padres` ENABLE KEYS */;
UNLOCK TABLES;


-- Añadir tabla para gestionar la configuración del campus
CREATE TABLE IF NOT EXISTS `configuracion_campus` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `clave` varchar(50) NOT NULL,
    `valor` text NOT NULL,
    `descripcion` varchar(255) DEFAULT NULL,
    `actualizado_en` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_configuracion_clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar configuración inicial
INSERT INTO `configuracion_campus` (`clave`, `valor`, `descripcion`) VALUES
    ('precio_base', '90', 'Precio base del campus'),
    ('descuento_segundo_hijo', '5', 'Descuento para el segundo hijo'),
    ('descuento_tercer_hijo', '10', 'Descuento para el tercer hijo'),
    ('iban_transferencia', 'ES29 30582519452720001546', 'IBAN para transferencias'),
    ('email_contacto', 'm_bustosramirez@yahoo.es', 'Email de contacto para pagos');

-- Añadir tabla para registrar pagos
CREATE TABLE IF NOT EXISTS `pagos` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `padre_id` int(11) NOT NULL,
    `monto` decimal(10,2) NOT NULL,
    `metodo` enum('transferencia','coordinador') NOT NULL,
    `estado` enum('pendiente','completado','cancelado') NOT NULL DEFAULT 'pendiente',
    `referencia` varchar(50) DEFAULT NULL,
    `fecha_pago` timestamp NULL DEFAULT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_pagos_padre` (`padre_id`),
    CONSTRAINT `fk_pagos_padre` FOREIGN KEY (`padre_id`) REFERENCES `padres` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Establecer charset y collation uniformes
ALTER DATABASE `ss_campus_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Optimizar motor de almacenamiento y charset
ALTER TABLE `padres` ENGINE=InnoDB;
ALTER TABLE `jugadores` ENGINE=InnoDB;
ALTER TABLE `descuentos` ENGINE=InnoDB;
ALTER TABLE `consentimientos` ENGINE=InnoDB;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
