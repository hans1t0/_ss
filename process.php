<?php
// Configuración de la base de datos
$config = [
    'host' => 'localhost',
    'dbname' => 'ss_campus_db',
    'user' => 'root',
    'password' => 'hans'
];

// Funciones de validación
function validarDNI($dni) {
    return preg_match('/^[0-9]{8}[A-Z]$/', $dni);
}

function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validarTelefono($telefono) {
    return preg_match('/^[0-9]{9}$/', $telefono);
}

function validarIBAN($iban) {
    return preg_match('/^ES[0-9]{22}$/', $iban);
}

function validarTexto($texto, $minLength = 2, $maxLength = 100) {
    $texto = trim($texto);
    return strlen($texto) >= $minLength && strlen($texto) <= $maxLength;
}

function limpiarEntrada($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

try {
    // Validar datos del padre/tutor
    $errores = [];
    
    $padre_nombre = limpiarEntrada($_POST['padre_nombre'] ?? '');
    $padre_dni = limpiarEntrada($_POST['padre_dni'] ?? '');
    $padre_telefono = limpiarEntrada($_POST['padre_telefono'] ?? '');
    $padre_email = limpiarEntrada($_POST['padre_email'] ?? '');
    $metodo_pago = limpiarEntrada($_POST['metodo_pago'] ?? '');
    $cuenta_bancaria = limpiarEntrada($_POST['cuenta_bancaria'] ?? '');

    if (!validarTexto($padre_nombre)) {
        $errores[] = "El nombre del padre/tutor no es válido";
    }
    if (!validarDNI($padre_dni)) {
        $errores[] = "El DNI no tiene un formato válido";
    }
    if (!validarTelefono($padre_telefono)) {
        $errores[] = "El teléfono no tiene un formato válido";
    }
    if (!validarEmail($padre_email)) {
        $errores[] = "El email no tiene un formato válido";
    }
    if ($metodo_pago === 'transferencia' && !validarIBAN($cuenta_bancaria)) {
        $errores[] = "El IBAN no tiene un formato válido";
    }

    // Si hay errores, detener el proceso
    if (!empty($errores)) {
        throw new Exception(implode(", ", $errores));
    }

    // Conexión a la base de datos
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['user'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

    // Iniciar transacción
    $pdo->beginTransaction();

    // Insertar datos validados del padre/tutor
    $stmtPadre = $pdo->prepare("
        INSERT INTO padres (nombre, dni, telefono, email, metodo_pago, cuenta_bancaria)
        VALUES (:nombre, :dni, :telefono, :email, :metodo_pago, :cuenta_bancaria)
    ");

    $stmtPadre->execute([
        ':nombre' => $padre_nombre,
        ':dni' => $padre_dni,
        ':telefono' => $padre_telefono,
        ':email' => $padre_email,
        ':metodo_pago' => $metodo_pago,
        ':cuenta_bancaria' => $metodo_pago === 'transferencia' ? $cuenta_bancaria : null
    ]);

    $padreId = $pdo->lastInsertId();

    // Validar y procesar datos de jugadores
    $stmtJugador = $pdo->prepare("
        INSERT INTO jugadores (
            padre_id, hijo_nombre_completo, hijo_fecha_nacimiento, sexo, 
            grupo, modalidad, demarcacion, lesiones, jugador_numero
        )
        VALUES (
            :padre_id, :nombre, :fecha_nac, :sexo, :grupo, 
            :modalidad, :demarcacion, :lesiones, :jugador_numero
        )
    ");

    $jugadorCount = 1;
    while (isset($_POST["hijo_nombre_completo_{$jugadorCount}"])) {
        // Validar datos del jugador
        $nombre = limpiarEntrada($_POST["hijo_nombre_completo_{$jugadorCount}"]);
        if (!validarTexto($nombre)) {
            throw new Exception("Nombre del jugador $jugadorCount no válido");
        }

        $fecha_nac = limpiarEntrada($_POST["hijo_fecha_nacimiento_{$jugadorCount}"]);
        if (!strtotime($fecha_nac)) {
            throw new Exception("Fecha de nacimiento del jugador $jugadorCount no válida");
        }

        $sexo = limpiarEntrada($_POST["sexo_{$jugadorCount}"]);
        if (!in_array($sexo, ['H', 'M'])) {
            throw new Exception("Sexo del jugador $jugadorCount no válido");
        }

        // Insertar datos validados del jugador
        $stmtJugador->execute([
            ':padre_id' => $padreId,
            ':nombre' => $nombre,
            ':fecha_nac' => $fecha_nac,
            ':sexo' => $sexo,
            ':grupo' => limpiarEntrada($_POST["grupo_{$jugadorCount}"]),
            ':modalidad' => limpiarEntrada($_POST["modalidad_{$jugadorCount}"]),
            ':demarcacion' => limpiarEntrada($_POST["demarcacion_{$jugadorCount}"]),
            ':lesiones' => limpiarEntrada($_POST["lesiones_{$jugadorCount}"] ?? ''),
            ':jugador_numero' => $jugadorCount
        ]);
        
        $jugadorCount++;
    }

    // Insertar consentimiento
    if (!isset($_POST['consentimiento']) || $_POST['consentimiento'] !== 'on') {
        throw new Exception("Debe aceptar el consentimiento de datos");
    }

    $stmtConsentimiento = $pdo->prepare("
        INSERT INTO consentimientos (padre_id, tipo, aceptado)
        VALUES (:padre_id, :tipo, :aceptado)
    ");
    
    $stmtConsentimiento->execute([
        ':padre_id' => $padreId,
        ':tipo' => 'datos',
        ':aceptado' => true
    ]);

    // Confirmar transacción
    $pdo->commit();

    // Respuesta exitosa con URL de redirección
    $response = [
        'status' => 'success',
        'message' => 'Inscripción completada correctamente',
        'inscripcion_id' => $padreId,
        'redirect_url' => "success.php?id=" . $padreId . "&metodo=" . $metodo_pago
    ];

} catch (Exception $e) {
    // Revertir transacción en caso de error
    if (isset($pdo)) {
        $pdo->rollBack();
    }

    // Log del error
    error_log("Error en inscripción: " . $e->getMessage());

    // Responder con error
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
}

// Enviar respuesta
header('Content-Type: application/json');
echo json_encode($response);
