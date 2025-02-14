<?php
// Configuración de la base de datos
$config = [
    'host' => 'mariadb',
    'dbname' => 'ss_campus_db',
    'user' => 'root',
    'password' => 'hansitox'
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

function logError($mensaje, $nivel = 'ERROR') {
    $fecha = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    error_log("[$fecha][$nivel][$ip] $mensaje");
}

try {
    // Validar datos del padre/tutor
    $errores = [];
    
    $padre_nombre = limpiarEntrada($_POST['padre_nombre'] ?? '');
    $padre_dni = limpiarEntrada($_POST['padre_dni'] ?? '');
    $padre_telefono = limpiarEntrada($_POST['padre_telefono'] ?? '');
    $padre_email = limpiarEntrada($_POST['padre_email'] ?? '');
    $metodo_pago = limpiarEntrada($_POST['metodo_pago'] ?? '');

    // Validar método de pago
    $metodo_pago = limpiarEntrada($_POST['metodo_pago'] ?? '');
    if (!in_array($metodo_pago, ['transferencia', 'coordinador'])) {
        $errores[] = "El método de pago no es válido";
    }

    // Modificar la sección de método de pago
    if ($metodo_pago === 'T') {
        $metodo_pago = 'transferencia';
    } elseif ($metodo_pago === 'C') {
        $metodo_pago = 'coordinador';
    }

    if (!in_array($metodo_pago, ['transferencia', 'coordinador'])) {
        $errores[] = "El método de pago no es válido";
    }

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
        INSERT INTO padres (nombre, dni, telefono, email, metodo_pago)
        VALUES (:nombre, :dni, :telefono, :email, :metodo_pago)
    ");

    $stmtPadre->execute([
        ':nombre' => $padre_nombre,
        ':dni' => $padre_dni,
        ':telefono' => $padre_telefono,
        ':email' => $padre_email,
        ':metodo_pago' => $metodo_pago
    ]);

    $padreId = $pdo->lastInsertId();

    // Validar y procesar datos de jugadores
    $stmtJugador = $pdo->prepare("
        INSERT INTO jugadores (
            padre_id, nombre_completo, fecha_nacimiento, sexo, 
            grupo, modalidad, demarcacion, lesiones, jugador_numero
        )
        VALUES (
            :padre_id, :nombre, :fecha_nac, :sexo, :grupo, 
            :modalidad, :demarcacion, :lesiones, :jugador_numero
        )
    ");

    $stmtDescuento = $pdo->prepare("
        INSERT INTO descuentos (jugador_id, descuento, tiene_hermanos)
        VALUES (:jugador_id, :descuento, :tiene_hermanos)
    ");

    $jugadorCount = 1;
    $precioTotal = 90; // Precio base del campus
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

        $jugadorId = $pdo->lastInsertId();
        $descuento = 0;
        $tieneHermanos = $jugadorCount > 1 ? 1 : 0;

        // Aplicar descuentos familiares
        if ($jugadorCount == 2) {
            $descuento = 5; // Descuento para el segundo hijo
            $precioTotal += (90 - $descuento);
        } elseif ($jugadorCount == 3) {
            $descuento = 10; // Descuento para el tercer hijo
            $precioTotal += (90 - $descuento);
        } else {
            $precioTotal += 90; // Precio completo para el primer hijo
        }

        // Insertar descuento en la tabla descuentos
        $stmtDescuento->execute([
            ':jugador_id' => $jugadorId,
            ':descuento' => $descuento,
            ':tiene_hermanos' => $tieneHermanos
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

    // Insertar consentimiento de imágenes
    if (!isset($_POST['consentimiento_imagen']) || $_POST['consentimiento_imagen'] !== 'on') {
        throw new Exception("Debe aceptar el consentimiento de imágenes");
    }

    $stmtConsentimiento->execute([
        ':padre_id' => $padreId,
        ':tipo' => 'imagen',
        ':aceptado' => true
    ]);

    // Confirmar transacción
    $pdo->commit();

    // Respuesta exitosa con URL de redirección
    $redirect_url = filter_var("success.php?id=" . $padreId . "&metodo=" . $metodo_pago . "&precio=" . $precioTotal, FILTER_SANITIZE_URL);
    $response = [
        'status' => 'success',
        'message' => 'Inscripción completada correctamente',
        'inscripcion_id' => $padreId,
        'redirect_url' => $redirect_url
    ];

} catch (Exception $e) {
    // Revertir transacción en caso de error
    if (isset($pdo)) {
        $pdo->rollBack();
    }

    // Log del error
    logError("Error en inscripción: " . $e->getMessage());

    // Responder con error
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
}

// Enviar respuesta
header('Content-Type: application/json');
echo json_encode($response);
