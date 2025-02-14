-- Script para optimizar el rendimiento de ss_campus_db

-- Optimizar índices para consultas frecuentes
ALTER TABLE jugadores
    ADD INDEX idx_jugador_grupo_modalidad (grupo, modalidad),
    ADD INDEX idx_jugador_nombre (nombre_completo),
    ADD INDEX idx_jugador_fecha_nacimiento (fecha_nacimiento);

ALTER TABLE padres
    ADD INDEX idx_padre_email_dni (email, dni),
    ADD INDEX idx_padre_metodo_pago (metodo_pago);

ALTER TABLE descuentos
    ADD INDEX idx_descuento_valor (descuento);

-- Optimizar motor de almacenamiento
ALTER TABLE jugadores ENGINE = InnoDB;
ALTER TABLE padres ENGINE = InnoDB;
ALTER TABLE descuentos ENGINE = InnoDB;
ALTER TABLE consentimientos ENGINE = InnoDB;

-- Optimizar charset para mejor rendimiento
ALTER DATABASE ss_campus_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE jugadores CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE padres CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE descuentos CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE consentimientos CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Analizar y optimizar tablas
ANALYZE TABLE jugadores, padres, descuentos, consentimientos;
OPTIMIZE TABLE jugadores, padres, descuentos, consentimientos;
