-- Crear y seleccionar la base de datos
DROP DATABASE IF EXISTS ss_campus_db;
CREATE DATABASE
IF NOT EXISTS ss_campus_db
    CHARACTER
SET utf8mb4
COLLATE utf8mb4_unicode_ci;
USE ss_campus_db;

-- Tabla para padres/tutores
CREATE TABLE padres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR
(100) NOT NULL,
    dni VARCHAR
(9) NOT NULL UNIQUE,
    telefono VARCHAR
(9) NOT NULL,
    email VARCHAR
(100) NOT NULL,
    metodo_pago ENUM
('T', 'C') NOT NULL,
    cuenta_bancaria VARCHAR
(24),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_padre_dni
(dni),
    INDEX idx_padre_email
(email)
) ENGINE=InnoDB;

-- Tabla para jugadores
CREATE TABLE jugadores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    padre_id INT NOT NULL,
    hijo_nombre_completo VARCHAR
(100) NOT NULL,
    hijo_fecha_nacimiento DATE NOT NULL,
    grupo ENUM
('Querubin', 'Prebenjamin', 'Benjamin', 'Alevin') NOT NULL,
    sexo ENUM
('H', 'M') NOT NULL,
    demarcacion ENUM
('jugador', 'portero') NOT NULL,
    modalidad ENUM
('RPSJ', 'NO_RPSJ') NOT NULL,
    lesiones TEXT,
    jugador_numero INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY
(padre_id) REFERENCES padres
(id) ON
DELETE CASCADE,
    INDEX idx_jugador_grupo (grupo),
    INDEX idx_jugador_modalidad
(modalidad),
    INDEX idx_jugador_numero
(jugador_numero)
) ENGINE=InnoDB;

-- Tabla para consentimientos
CREATE TABLE consentimientos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    padre_id INT NOT NULL,
    tipo ENUM
('datos', 'imagen') NOT NULL,
    aceptado BOOLEAN DEFAULT true,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY
(padre_id) REFERENCES padres
(id) ON
DELETE CASCADE,
    UNIQUE KEY uk_padre_tipo (padre_id, tipo
)
) ENGINE=InnoDB;

-- Crear vistas para estadísticas
CREATE OR REPLACE VIEW v_estadisticas_grupos AS
SELECT
    grupo,
    COUNT(*) as total
FROM jugadores
GROUP BY grupo;

CREATE OR REPLACE VIEW v_estadisticas_pagos AS
SELECT
    metodo_pago,
    COUNT(*) as total
FROM padres
GROUP BY metodo_pago;

CREATE OR REPLACE VIEW v_estadisticas_jugadores AS
    SELECT
        'sexo' as tipo,
        sexo as valor,
        COUNT(*) as total
    FROM jugadores
    GROUP BY sexo
UNION ALL
    SELECT
        'demarcacion' as tipo,
        demarcacion as valor,
        COUNT(*) as total
    FROM jugadores
    GROUP BY demarcacion;

-- Triggers para validación
DELIMITER //

CREATE TRIGGER before_insert_jugador
BEFORE
INSERT ON
jugadores
FOR
EACH
ROW
BEGIN
    IF NEW.hijo_fecha_nacimiento > CURDATE() THEN
        SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT
    = 'La fecha de nacimiento no puede ser futura';
END
IF;
END//

CREATE TRIGGER before_insert_padre
BEFORE
INSERT ON
padres
FOR
EACH
ROW
BEGIN
    IF NEW.metodo_pago = 'T' AND NEW.cuenta_bancaria IS NULL THEN
        SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT
    = 'La cuenta bancaria es obligatoria para pago por transferencia';
END
IF;
END//

DELIMITER ;

-- Procedimientos almacenados útiles
DELIMITER //

CREATE PROCEDURE sp_estadisticas_completas()
BEGIN
    SELECT
        (SELECT COUNT(*)
        FROM padres) as total_inscripciones,
        (SELECT COUNT(*)
        FROM jugadores) as total_jugadores,
        (SELECT COUNT(*)
        FROM padres
        WHERE metodo_pago = 'C') as pendiente_pago;

    SELECT *
    FROM v_estadisticas_grupos;
    SELECT *
    FROM v_estadisticas_pagos;
    SELECT *
    FROM v_estadisticas_jugadores;
END
//

DELIMITER ;
