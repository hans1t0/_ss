<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', __DIR__ . '/debug.log');

function logDebug($message) {
    error_log(date('Y-m-d H:i:s') . " - " . $message . "\n");
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

    // 3. Recoger datos básicos
    $padre = [
        'nombre' => trim($_POST['padre_nombre'] ?? ''),
        'dni' => trim($_POST['padre_dni'] ?? ''),
        'telefono' => trim($_POST['padre_telefono'] ?? ''),
        'email' => trim($_POST['padre_email'] ?? ''),
        'metodo_pago' => trim($_POST['metodo_pago'] ?? '')
    ];

    logDebug("Datos del padre: " . print_r($padre, true));

    // 4. Validación básica
    if (empty($padre['nombre']) || empty($padre['dni']) || empty($padre['email'])) {
        throw new Exception("Faltan campos obligatorios");
    }

    // 5. Insertar en la base de datos
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO padres (nombre, dni, telefono, email, metodo_pago) 
            VALUES (:nombre, :dni, :telefono, :email, :metodo_pago)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($padre);
    
    $padreId = $pdo->lastInsertId();
    $pdo->commit();
    logDebug("Registro completado con éxito. ID: " . $padreId);

    // Construir URL de redirección de manera más segura
    $redirectUrl = sprintf(
        "success.php?id=%d&metodo=%s&token=%s",
        $padreId,
        urlencode($padre['metodo_pago']),
        urlencode(hash('sha256', $padreId . $_SERVER['REQUEST_TIME']))
    );

    // Enviar respuesta
    sendJsonResponse([
        'status' => 'success',
        'message' => 'Registro completado correctamente',
        'redirect_url' => $redirectUrl,
        'debug_info' => [
            'id' => $padreId,
            'metodo' => $padre['metodo_pago']
        ]
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
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    sendJsonResponse([
        'status' => 'error',
        'message' => $e->getMessage()
    ], 400);
}
