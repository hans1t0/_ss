-- Script de actualización de la base de datos ss_campus_db

-- Hacer backup de tablas existentes
CREATE TABLE IF NOT EXISTS jugadores_backup LIKE jugadores;
INSERT INTO jugadores_backup SELECT * FROM jugadores;

CREATE TABLE IF NOT EXISTS padres_backup LIKE padres;
INSERT INTO padres_backup SELECT * FROM padres;

-- Añadir nuevas columnas a la tabla padres
ALTER TABLE padres
    ADD COLUMN estado_pago enum('pendiente','pagado','cancelado') DEFAULT 'pendiente' AFTER metodo_pago,
    ADD COLUMN notas_admin text DEFAULT NULL,
    ADD COLUMN fecha_actualizacion timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN usuario_modificacion varchar(50) DEFAULT NULL;

-- Añadir nuevas columnas a la tabla jugadores
ALTER TABLE jugadores
    ADD COLUMN talla_camiseta enum('4','6','8','10','12','14','S','M','L','XL') DEFAULT NULL,
    ADD COLUMN alergias text DEFAULT NULL,
    ADD COLUMN medicacion text DEFAULT NULL,
    ADD COLUMN foto_perfil varchar(255) DEFAULT NULL,
    ADD COLUMN estado enum('activo','inactivo') DEFAULT 'activo';

-- Crear tabla para equipamiento
CREATE TABLE IF NOT EXISTS equipamiento (
    id int(11) NOT NULL AUTO_INCREMENT,
    jugador_id int(11) NOT NULL,
    tipo enum('camiseta','pantalon','medias','balon') NOT NULL,
    talla varchar(10) DEFAULT NULL,
    entregado tinyint(1) DEFAULT 0,
    fecha_entrega timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY fk_equipamiento_jugador (jugador_id),
    CONSTRAINT fk_equipamiento_jugador FOREIGN KEY (jugador_id) REFERENCES jugadores (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla para asistencia
CREATE TABLE IF NOT EXISTS asistencia (
    id int(11) NOT NULL AUTO_INCREMENT,
    jugador_id int(11) NOT NULL,
    fecha date NOT NULL,
    asistio tinyint(1) DEFAULT 1,
    observaciones text DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_asistencia_jugador_fecha (jugador_id, fecha),
    CONSTRAINT fk_asistencia_jugador FOREIGN KEY (jugador_id) REFERENCES jugadores (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla para grupos y monitores
CREATE TABLE IF NOT EXISTS grupos (
    id int(11) NOT NULL AUTO_INCREMENT,
    nombre varchar(50) NOT NULL,
    descripcion text DEFAULT NULL,
    monitor_principal varchar(100) DEFAULT NULL,
    monitor_auxiliar varchar(100) DEFAULT NULL,
    horario varchar(100) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla para documentos
CREATE TABLE IF NOT EXISTS documentos (
    id int(11) NOT NULL AUTO_INCREMENT,
    padre_id int(11) NOT NULL,
    tipo enum('dni','foto','certificado_medico','otros') NOT NULL,
    nombre_archivo varchar(255) NOT NULL,
    ruta_archivo varchar(255) NOT NULL,
    fecha_subida timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY fk_documentos_padre (padre_id),
    CONSTRAINT fk_documentos_padre FOREIGN KEY (padre_id) REFERENCES padres (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Actualizar configuración del campus
INSERT INTO configuracion_campus (clave, valor, descripcion) VALUES
    ('fecha_inicio_campus', '2024-07-01', 'Fecha de inicio del campus'),
    ('fecha_fin_campus', '2024-07-31', 'Fecha de finalización del campus'),
    ('hora_inicio', '09:00', 'Hora de inicio de actividades'),
    ('hora_fin', '14:00', 'Hora de finalización de actividades'),
    ('max_participantes', '100', 'Número máximo de participantes')
ON DUPLICATE KEY UPDATE valor=VALUES(valor);

-- Agregar índices para optimizar búsquedas
ALTER TABLE jugadores
    ADD INDEX idx_jugador_estado (estado),
    ADD INDEX idx_jugador_talla (talla_camiseta);

ALTER TABLE padres
    ADD INDEX idx_padre_estado_pago (estado_pago);

-- Añadir triggers para auditoría
DELIMITER //
CREATE TRIGGER tr_update_padre BEFORE UPDATE ON padres
FOR EACH ROW
BEGIN
    SET NEW.fecha_actualizacion = CURRENT_TIMESTAMP;
END//
DELIMITER ;

-- Procedimiento para generar informe de pagos
DELIMITER //
CREATE PROCEDURE sp_informe_pagos()
BEGIN
    SELECT 
        p.nombre,
        p.dni,
        p.email,
        p.metodo_pago,
        p.estado_pago,
        COUNT(j.id) as num_hijos,
        SUM(CASE 
            WHEN d.descuento IS NULL THEN 90
            ELSE 90 - d.descuento 
        END) as total_a_pagar
    FROM padres p
    LEFT JOIN jugadores j ON p.id = j.padre_id
    LEFT JOIN descuentos d ON j.id = d.jugador_id
    GROUP BY p.id;
END//
DELIMITER ;
