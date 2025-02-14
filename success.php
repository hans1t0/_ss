<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', __DIR__ . '/success_debug.log');

function logDebug($message, $data = []) {
    error_log(date('Y-m-d H:i:s') . " - " . $message . " - Data: " . print_r($data, true));
}

// Verificar parámetros
$inscripcionId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$metodoPago = filter_input(INPUT_GET, 'metodo', FILTER_SANITIZE_SPECIAL_CHARS);
$precioTotal = filter_input(INPUT_GET, 'precio', FILTER_VALIDATE_INT);

logDebug("Parámetros recibidos", ['id' => $inscripcionId, 'metodo' => $metodoPago, 'precio' => $precioTotal]);

try {
    require_once 'config/database.php';
    
    if (!isset($pdo)) {
        throw new Exception("No se pudo establecer la conexión con la base de datos");
    }

    logDebug("Conexión establecida, buscando inscripción", ['id' => $inscripcionId]);

    // Obtener datos del padre con una sola consulta
    $stmtPadre = $pdo->prepare("
        SELECT 
            p.*
        FROM padres p
        WHERE p.id = ?
    ");

    $stmtPadre->execute([$inscripcionId]);
    $datos = $stmtPadre->fetch(PDO::FETCH_ASSOC);

    if (!$datos) {
        throw new Exception("No se encontró la inscripción con ID: " . $inscripcionId);
    }

    // Obtener datos de los jugadores con descuentos
    $stmtJugadores = $pdo->prepare("
        SELECT j.*, d.descuento
        FROM jugadores j
        LEFT JOIN descuentos d ON j.id = d.jugador_id
        WHERE j.padre_id = ?
        ORDER BY j.jugador_numero
    ");
    $stmtJugadores->execute([$inscripcionId]);
    $jugadores = $stmtJugadores->fetchAll(PDO::FETCH_ASSOC);

    logDebug("Datos recuperados", [
        'padre' => $datos,
        'jugadores' => $jugadores
    ]);

} catch (PDOException $e) {
    logDebug("Error de base de datos", [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    header('Location: index.php?status=error&message=' . urlencode('Error en la base de datos: ' . $e->getMessage()));
    exit();
} catch (Exception $e) {
    logDebug("Error general", [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    header('Location: index.php?status=error&message=' . urlencode($e->getMessage()));
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción Completada | Racing Playa San Juan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body text-center p-5">
                        <i class="fas fa-check-circle text-success mb-4" style="font-size: 4rem;"></i>
                        
                        <h1 class="display-4 mb-4">¡Inscripción Completada!</h1>
                        
                        <p class='lead mb-4'>Número de inscripción: #<?= htmlspecialchars($inscripcionId) ?></p>

                        <p class="lead">
                            Gracias <?= htmlspecialchars($datos['nombre']) ?> por inscribir a:
                        </p>
                        <ul class="list-unstyled">
                            <?php foreach ($jugadores as $jugador): ?>
                                <li>
                                    <?= htmlspecialchars($jugador['nombre_completo']) ?> 
                                    (<?= htmlspecialchars($jugador['grupo']) ?>)
                                    <?php if ($jugador['descuento'] > 0): ?>
                                        <span class="badge bg-success">
                                            Descuento: <?= htmlspecialchars($jugador['descuento']) ?>€
                                        </span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <p class='lead mb-4'>Precio Total: <?= htmlspecialchars($precioTotal) ?> €</p>

                        <div class="alert alert-info mb-4">
                            <h4 class="alert-heading mb-3">Próximos Pasos</h4>
                            <div id="info_pago" class="text-start">
                                <?php
                                if ($datos['metodo_pago'] === 'transferencia') {
                                    echo "
                                    <p><strong>Datos para la transferencia:</strong></p>
                                    <ul>
                                        <li>IBAN: ES29 30582519452720001546</li>
                                        <li>Beneficiario: Racing Playa San Juan C.D.</li>
                                        <li>Concepto: CampusSS + Nombre del jugador</li>
                                    </ul>
                                    <p>Por favor, envíe el justificante de pago a: m_bustosramirez@yahoo.es</p>";
                                } else {
                                    echo "
                                    <p>Por favor, contacte con el coordinador para realizar el pago.</p>
                                    <p>El coordinador se pondrá en contacto con usted para acordar el momento del pago.</p>";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="alert alert-success mb-4">
                            <p class="mb-0">
                                Hemos enviado un correo electrónico con toda la información a la dirección proporcionada.
                                Si no lo recibe en los próximos minutos, por favor revise su carpeta de spam.
                            </p>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="inscripcion.php" class="btn btn-primary btn-lg">
                                <i class="fas fa-home"></i> Volver al Inicio
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-print"></i> Imprimir Comprobante
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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
        window.location.href = `mailto:<?= $datos['email'] ?>?subject=Detalles de Inscripción #<?= $inscripcionId ?>&body=Adjunto información de la inscripción...`;
    }
    </script>
</body>
</html>
