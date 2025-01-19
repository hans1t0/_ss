<?php
session_start();

$config = [
    'host' => 'localhost',
    'dbname' => 'ss_campus_db',
    'user' => 'root',
    'password' => 'hans'
];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['user'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Iniciar transacción
    $pdo->beginTransaction();

    // Eliminar consentimientos
    $stmt = $pdo->prepare("DELETE FROM consentimientos WHERE padre_id = ?");
    $stmt->execute([$id]);

    // Eliminar jugadores
    $stmt = $pdo->prepare("DELETE FROM jugadores WHERE padre_id = ?");
    $stmt->execute([$id]);

    // Eliminar padre
    $stmt = $pdo->prepare("DELETE FROM padres WHERE id = ?");
    $stmt->execute([$id]);

    // Confirmar transacción
    $pdo->commit();

    header('Location: dashboard.php?status=success&message=Inscripción eliminada correctamente');

} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    header('Location: dashboard.php?status=error&message=' . urlencode($e->getMessage()));
}
