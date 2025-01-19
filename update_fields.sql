-- Primero, hacer backup de las tablas existentes
CREATE TABLE jugadores_backup AS
SELECT *
FROM jugadores;

-- Eliminar las restricciones de clave foránea existentes
ALTER TABLE jugadores DROP FOREIGN KEY jugadores_ibfk_1;

-- Eliminar la tabla original
DROP TABLE jugadores;

-- Crear la tabla actualizada
CREATE TABLE jugadores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    padre_id INT NOT NULL,
    hijo_nombre_completo VARCHAR
(100) NOT NULL,
    hijo_fecha_nacimiento DATE NOT NULL,
    grupo VARCHAR
(20) NOT NULL,
    sexo ENUM
('H', 'M') NOT NULL,
    demarcacion ENUM
('jugador', 'portero') NOT NULL,
    modalidad ENUM
('RPSJ', 'NO_RPSJ') NOT NULL,
    lesiones TEXT,
    jugador_numero INT NOT NULL,
    FOREIGN KEY
(padre_id) REFERENCES padres
(id),
    INDEX idx_jugador_numero
(jugador_numero)
);

-- Recrear los índices
CREATE INDEX idx_jugador_grupo ON jugadores(grupo);
CREATE INDEX idx_jugador_modalidad ON jugadores(modalidad);
