<?php
error_log("Iniciando configuración de base de datos");

$DB_CONFIG = [
    'host' => 'localhost', // Volvemos a localhost
    'socket' => '/Applications/MAMP/tmp/mysql/mysql.sock', // Añadir socket path
    'dbname' => 'ss_campus_db',
    'user' => 'root',
    'password' => 'hans'
];

try {
    error_log("Intentando conexión...");
    
    // Intentar primero con socket
    try {
        $dsn = "mysql:unix_socket={$DB_CONFIG['socket']};dbname={$DB_CONFIG['dbname']};charset=utf8mb4";
        $pdo = new PDO($dsn, $DB_CONFIG['user'], $DB_CONFIG['password']);
        error_log("Conexión exitosa usando socket");
    } 
    // Si falla el socket, intentar con TCP
    catch (PDOException $e) {
        error_log("Fallo conexión por socket, intentando TCP...");
        $dsn = "mysql:host={$DB_CONFIG['host']};dbname={$DB_CONFIG['dbname']};charset=utf8mb4";
        $pdo = new PDO($dsn, $DB_CONFIG['user'], $DB_CONFIG['password']);
        error_log("Conexión exitosa usando TCP");
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // Verificar conexión
    $pdo->query("SELECT 1");
    
} catch(PDOException $e) {
    error_log("Error final de conexión: " . $e->getMessage());
    
    if (php_sapi_name() !== 'cli') {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Error de conexión a la base de datos',
            'debug' => $e->getMessage()
        ]);
        exit;
    }
    throw $e;
}
