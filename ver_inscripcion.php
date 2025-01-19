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

    // Obtener datos del padre
    $stmt = $pdo->prepare("
        SELECT *
        FROM padres 
        WHERE id = ?
    ");
    $stmt->execute([$id]);
    $padre = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$padre) {
        throw new Exception('Inscripción no encontrada');
    }

    // Obtener jugadores
    $stmt = $pdo->prepare("
        SELECT *
        FROM jugadores
        WHERE padre_id = ?
        ORDER BY jugador_numero
    ");
    $stmt->execute([$id]);
    $jugadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Inscripción #<?= $id ?> - Campus de Fútbol</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-4">
        <!-- Barra superior con acciones -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
            <div class="btn-group">
                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button onclick="exportarPDF()" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </button>
                <button onclick="enviarEmail()" class="btn btn-outline-success">
                    <i class="fas fa-envelope"></i> Enviar Email
                </button>
            </div>
        </div>

        <!-- Encabezado con información principal -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-file-alt"></i> Inscripción #<?= $id ?>
                    </h3>
                    <span class="badge bg-light text-primary">
                        <?= date('d/m/Y H:i', strtotime($padre['fecha_registro'])) ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-group">
                            <label class="text-muted">Padre/Tutor</label>
                            <h4><?= htmlspecialchars($padre['nombre']) ?></h4>
                            <p class="mb-1">DNI: <?= htmlspecialchars($padre['dni']) ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group">
                            <label class="text-muted">Contacto</label>
                            <p class="mb-1">
                                <i class="fas fa-envelope"></i> 
                                <a href="mailto:<?= htmlspecialchars($padre['email']) ?>">
                                    <?= htmlspecialchars($padre['email']) ?>
                                </a>
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-phone"></i> 
                                <a href="tel:<?= htmlspecialchars($padre['telefono']) ?>">
                                    <?= htmlspecialchars($padre['telefono']) ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <span class="badge bg-<?= $padre['metodo_pago'] === 'T' ? 'success' : 'warning' ?> p-2">
                            <i class="fas fa-<?= $padre['metodo_pago'] === 'T' ? 'university' : 'hand-holding-usd' ?>"></i>
                            <?= $padre['metodo_pago'] === 'T' ? 'Transferencia' : 'Pago al Coordinador' ?>
                        </span>
                    </div>
                    <?php if ($padre['metodo_pago'] === 'T'): ?>
                        <small class="text-muted">
                            IBAN: <?= htmlspecialchars($padre['cuenta_bancaria']) ?>
                        </small>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Jugadores -->
        <div class="row">
            <?php foreach ($jugadores as $index => $jugador): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-user"></i> 
                            <?= $index === 0 ? 'Jugador Principal' : 'Hermano ' . $index ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title"><?= htmlspecialchars($jugador['hijo_nombre_completo']) ?></h4>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="text-muted d-block">Fecha Nacimiento</label>
                                <span><?= date('d/m/Y', strtotime($jugador['hijo_fecha_nacimiento'])) ?></span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted d-block">Grupo</label>
                                <span class="badge bg-info"><?= htmlspecialchars($jugador['grupo']) ?></span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted d-block">Sexo</label>
                                <i class="fas fa-<?= $jugador['sexo'] === 'H' ? 'male' : 'female' ?>"></i>
                                <?= $jugador['sexo'] === 'H' ? 'Hombre' : 'Mujer' ?>
                            </div>
                            <div class="col-6">
                                <label class="text-muted d-block">Demarcación</label>
                                <i class="fas fa-<?= $jugador['demarcacion'] === 'portero' ? 'hands' : 'running' ?>"></i>
                                <?= ucfirst($jugador['demarcacion']) ?>
                            </div>
                            <div class="col-12">
                                <label class="text-muted d-block">Modalidad</label>
                                <span class="badge bg-<?= $jugador['modalidad'] === 'RPSJ' ? 'primary' : 'secondary' ?>">
                                    <?= $jugador['modalidad'] ?>
                                </span>
                            </div>
                            <?php if ($jugador['lesiones']): ?>
                            <div class="col-12">
                                <label class="text-muted d-block">Lesiones/Alergias</label>
                                <div class="alert alert-warning mb-0">
                                    <?= htmlspecialchars($jugador['lesiones']) ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
    @media print {
        .btn, .no-print { display: none !important; }
        .card { border: 1px solid #dee2e6 !important; }
        .badge { border: 1px solid #666 !important; }
    }
    .info-group { margin-bottom: 1.5rem; }
    .info-group label { font-size: 0.875rem; display: block; margin-bottom: 0.25rem; }
    .info-group h4 { margin-bottom: 0.5rem; }
    .badge { font-size: 0.9rem; }
    .card { box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
    </style>

    <script>
    function exportarPDF() {
        // Implementar exportación a PDF
        alert('Función de exportación a PDF en desarrollo');
    }

    function enviarEmail() {
        window.location.href = `mailto:<?= $padre['email'] ?>?subject=Detalles de Inscripción #<?= $id ?>&body=Adjunto información de la inscripción...`;
    }
    </script>
</body>
</html>
