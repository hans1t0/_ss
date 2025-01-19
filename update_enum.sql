
CREATE DATABASE
IF NOT EXISTS ss_campus_db;
USE ss_campus_db;

-- Tabla para padres/tutores
CREATE TABLE padres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR
(100) NOT NULL,
    dni VARCHAR
(9) NOT NULL,
    telefono VARCHAR
(9) NOT NULL,
    email VARCHAR
(100) NOT NULL,
    metodo_pago ENUM
('T', 'C') NOT NULL,
    cuenta_bancaria VARCHAR
(24),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla para jugadores
CREATE TABLE jugadores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    padre_id INT NOT NULL,
    nombre_completo VARCHAR
(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    sexo ENUM
('H', 'M') NOT NULL,
    grupo ENUM
('Querubin', 'Prebenjamin', 'Benjamin', 'Alevin') NOT NULL,
    modalidad ENUM
('RPSJ', 'NO_RPSJ') NOT NULL,
    demarcacion ENUM
('jugador', 'portero') NOT NULL,
    lesiones TEXT,
    FOREIGN KEY
(padre_id) REFERENCES padres
(id)
);

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
(id)
);

-- Índices para optimizar búsquedas
CREATE INDEX idx_padre_dni ON padres(dni);
CREATE INDEX idx_jugador_grupo ON jugadores(grupo);
CREATE INDEX idx_jugador_modalidad ON jugadores(modalidad);

-- Primero actualizamos los valores existentes
UPDATE padres SET metodo_pago = 'T' WHERE metodo_pago = 'transferencia';
UPDATE padres SET metodo_pago = 'C' WHERE metodo_pago = 'coordinador';

-- Luego modificamos la columna
ALTER TABLE padres 
    MODIFY COLUMN metodo_pago ENUM
('T', 'C') NOT NULL;