-- Tabla para descuentos
CREATE TABLE
    descuentos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        jugador_id INT NOT NULL,
        descuento DECIMAL(5, 2) NOT NULL,
        tiene_hermanos BOOLEAN NOT NULL DEFAULT FALSE,
        FOREIGN KEY (jugador_id) REFERENCES jugadores (id),
        INDEX (jugador_id)
    );

CREATE TABLE
    padres (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nombre VARCHAR(100) NOT NULL,
        dni VARCHAR(9) NOT NULL,
        telefono VARCHAR(9) NOT NULL,
        email VARCHAR(100) NOT NULL,
        metodo_pago ENUM ('transferencia', 'coordinador') NOT NULL,
        cuenta_bancaria VARCHAR(24),
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (dni),
        INDEX (email)
    );

-- Tabla para jugadores
CREATE TABLE
    jugadores (
        id INT PRIMARY KEY AUTO_INCREMENT,
        padre_id INT NOT NULL,
        nombre_completo VARCHAR(100) NOT NULL,
        fecha_nacimiento DATE NOT NULL,
        sexo ENUM ('H', 'M') NOT NULL,
        grupo ENUM ('Querubin', 'Prebenjamin', 'Benjamin', 'Alevin') NOT NULL,
        modalidad ENUM ('RPSJ', 'NO_RPSJ') NOT NULL,
        demarcacion ENUM ('jugador', 'portero') NOT NULL,
        lesiones TEXT,
        jugador_numero INT NOT NULL,
        FOREIGN KEY (padre_id) REFERENCES padres (id),
        INDEX (padre_id)
    );

-- Tabla para consentimientos
CREATE TABLE
    consentimientos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        padre_id INT NOT NULL,
        tipo ENUM ('datos', 'imagen') NOT NULL,
        aceptado BOOLEAN NOT NULL DEFAULT FALSE,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (padre_id) REFERENCES padres (id),
        INDEX (padre_id)
    );