<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', __DIR__ . '/debug.log');

function logDebug($message, $data = []) {
    error_log(date('Y-m-d H:i:s') . " - " . $message . " - Data: " . print_r($data, true));
}

function sendJsonResponse($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

try {
    logDebug("Iniciando procesamiento");
    logDebug("POST data: " . print_r($_POST, true));

    // 1. Verificar datos POST
    if (empty($_POST)) {
        throw new Exception("No se recibieron datos del formulario");
    }

    // 2. Conexión a la base de datos
    $db = [
        'host' => 'localhost',
        'name' => 'ss_campus_db',
        'user' => 'root',
        'pass' => 'hans'
    ];

    logDebug("Intentando conectar a la base de datos");
    
    $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Probar conexión
    $pdo->query("SELECT 1");
    logDebug("Conexión establecida");

    // 3. Recoger datos básicos del padre
    $padre = [
        'nombre' => trim($_POST['padre_nombre'] ?? ''),
        'dni' => trim($_POST['padre_dni'] ?? ''),
        'telefono' => trim($_POST['padre_telefono'] ?? ''),
        'email' => trim($_POST['padre_email'] ?? ''),
        'metodo_pago' => trim($_POST['metodo_pago'] ?? '')
    ];

    logDebug("Datos del padre: " . print_r($padre, true));

    // 4. Validación básica del padre
    if (empty($padre['nombre']) || empty($padre['dni']) || empty($padre['email'])) {
        throw new Exception("Faltan campos obligatorios del padre");
    }

    // 5. Insertar datos del padre en la base de datos
    $pdo->beginTransaction();
    
    $sqlPadre = "INSERT INTO padres (nombre, dni, telefono, email, metodo_pago) 
            VALUES (:nombre, :dni, :telefono, :email, :metodo_pago)";
    
    $stmtPadre = $pdo->prepare($sqlPadre);
    $stmtPadre->execute([
        ':nombre' => $padre['nombre'],
        ':dni' => $padre['dni'],
        ':telefono' => $padre['telefono'],
        ':email' => $padre['email'],
        ':metodo_pago' => $padre['metodo_pago']
    ]);
    
    $padreId = $pdo->lastInsertId();
    logDebug("Registro del padre exitoso. ID: " . $padreId);

    // 6. Recoger datos de los hijos
    $hijos = [];
    $jugadorCount = 1;
    $descuentos = [0, 5, 10]; // Descuentos para el 1er, 2do y 3er hijo
    $precioTotal = 0;

    while (isset($_POST["hijo_nombre_completo_$jugadorCount"])) {
        $sexo = trim($_POST["sexo_$jugadorCount"] ?? '');
        if (!in_array($sexo, ['H', 'M'])) {
            throw new Exception("Sexo del jugador $jugadorCount no válido");
        }
        
        $descuento = $descuentos[min($jugadorCount - 1, count($descuentos) - 1)];
        $tieneHermanos = $jugadorCount > 1 ? 1 : 0;

        $hijos[] = [
            'padre_id' => $padreId,
            'nombre_completo' => trim($_POST["hijo_nombre_completo_$jugadorCount"] ?? ''),
            'fecha_nacimiento' => trim($_POST["hijo_fecha_nacimiento_$jugadorCount"] ?? ''),
            'sexo' => $sexo,
            'grupo' => trim($_POST["grupo_$jugadorCount"] ?? ''),
            'modalidad' => trim($_POST["modalidad_$jugadorCount"] ?? ''),
            'demarcacion' => trim($_POST["demarcacion_$jugadorCount"] ?? ''),
            'lesiones' => trim($_POST["lesiones_$jugadorCount"] ?? ''),
            'jugador_numero' => $jugadorCount,
            'descuento' => $descuento,
            'tiene_hermanos' => $tieneHermanos
        ];
        $precioTotal += 90 - $descuento;
        $jugadorCount++;
    }

    logDebug("Datos de los hijos: " . print_r($hijos, true));

    // 7. Insertar datos de los hijos
    $sqlJugador = "INSERT INTO jugadores (padre_id, nombre_completo, fecha_nacimiento, sexo, grupo, modalidad, demarcacion, lesiones, jugador_numero) 
                   VALUES (:padre_id, :nombre_completo, :fecha_nacimiento, :sexo, :grupo, :modalidad, :demarcacion, :lesiones, :jugador_numero)";
    $stmtJugador = $pdo->prepare($sqlJugador);

    $sqlDescuento = "INSERT INTO descuentos (jugador_id, descuento, tiene_hermanos) 
                      VALUES (:jugador_id, :descuento, :tiene_hermanos)";
    $stmtDescuento = $pdo->prepare($sqlDescuento);

    foreach ($hijos as $hijo) {
        $stmtJugador->execute([
            ':padre_id' => $hijo['padre_id'],
            ':nombre_completo' => $hijo['nombre_completo'],
            ':fecha_nacimiento' => $hijo['fecha_nacimiento'],
            ':sexo' => $hijo['sexo'],
            ':grupo' => $hijo['grupo'],
            ':modalidad' => $hijo['modalidad'],
            ':demarcacion' => $hijo['demarcacion'],
            ':lesiones' => $hijo['lesiones'],
            ':jugador_numero' => $hijo['jugador_numero']
        ]);
        $jugadorId = $pdo->lastInsertId();

        $stmtDescuento->execute([
            ':jugador_id' => $jugadorId,
            ':descuento' => $hijo['descuento'],
            ':tiene_hermanos' => $hijo['tiene_hermanos']
        ]);

        logDebug("Hijo registrado: " . $hijo['nombre_completo']);
    }

    // Insertar consentimiento
    $consentimiento = isset($_POST['consentimiento']) && $_POST['consentimiento'] === 'on' ? 1 : 0;
    $consentimiento_imagen = isset($_POST['consentimiento_imagen']) && $_POST['consentimiento_imagen'] === 'on' ? 1 : 0;

    $sqlConsentimiento = "INSERT INTO consentimientos (padre_id, tipo, aceptado) 
                          VALUES (:padre_id, :tipo, :aceptado)";
    $stmtConsentimiento = $pdo->prepare($sqlConsentimiento);

    $stmtConsentimiento->execute([
        ':padre_id' => $padreId,
        ':tipo' => 'datos',
        ':aceptado' => $consentimiento
    ]);

    $stmtConsentimiento->execute([
        ':padre_id' => $padreId,
        ':tipo' => 'imagen',
        ':aceptado' => $consentimiento_imagen
    ]);

    logDebug("Consentimientos registrados");

    $pdo->commit();
    logDebug("Transacción completada con éxito");

    // Construir URL de redirección
    $redirectUrl = sprintf(
        "inscripcion.php?id=%d&metodo=%s&precio=%d",
        $padreId,
        urlencode($padre['metodo_pago']),
        $precioTotal
    );

    // Enviar respuesta
    sendJsonResponse([
        'status' => 'success',
        'message' => 'Registro completado correctamente',
        'redirect_url' => $redirectUrl
    ]);

} catch (PDOException $e) {
    logDebug("Error de base de datos: " . $e->getMessage());
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    sendJsonResponse([
        'status' => 'error',
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ], 500);

} catch (Exception $e) {
    logDebug("Error general: " . $e->getMessage());
    sendJsonResponse([
        'status' => 'error',
        'message' => $e->getMessage()
    ], 400);
}
