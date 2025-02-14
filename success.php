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

logDebug("Parámetros recibidos", ['id' => $inscripcionId, 'metodo' => $metodoPago]);

try {
    require_once 'config/database.php';
    
    if (!isset($pdo)) {
        throw new Exception("No se pudo establecer la conexión con la base de datos");
    }

    logDebug("Conexión establecida, buscando inscripción", ['id' => $inscripcionId]);

    // Obtener datos del padre con una sola consulta
    $stmtPadre = $pdo->prepare("
        SELECT 
            p.*,
            GROUP_CONCAT(
                CONCAT_WS('|', 
                    j.nombre_completo, 
                    j.grupo,
                    j.jugador_numero
                )
                ORDER BY j.jugador_numero
                SEPARATOR ';'
            ) as jugadores_info
        FROM padres p
        LEFT JOIN jugadores j ON p.id = j.padre_id
        WHERE p.id = ?
        GROUP BY p.id
    ");

    $stmtPadre->execute([$inscripcionId]);
    $datos = $stmtPadre->fetch(PDO::FETCH_ASSOC);

    logDebug("Datos recuperados", $datos);

    if (!$datos) {
        throw new Exception("No se encontró la inscripción con ID: " . $inscripcionId);
    }

    // Procesar datos de jugadores
    $jugadores = [];
    if ($datos['jugadores_info']) {
        foreach (explode(';', $datos['jugadores_info']) as $jugador) {
            list($nombre, $grupo, $numero) = explode('|', $jugador);
            $jugadores[] = [
                'nombre_completo' => $nombre,
                'grupo' => $grupo,
                'numero' => $numero
            ];
        }
    }

    logDebug("Jugadores procesados", $jugadores);

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
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="alert alert-info mb-4">
                            <h4 class="alert-heading mb-3">Próximos Pasos</h4>
                            <div id="info_pago" class="text-start">
                                <?php
                                if ($datos['metodo_pago'] === 'T') {
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
                            <a href="index.php" class="btn btn-primary btn-lg">
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
        .btn {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
        }
        body {
            background-color: white !important;
        }
    }

    .alert {
        border-left-width: 4px;
    }

    .alert-info {
        border-left-color: #0dcaf0;
    }

    .alert-success {
        border-left-color: #198754;
    }

    .card {
        border: none;
        border-radius: 1rem;
    }

    .fas.fa-check-circle {
        color: #198754;
    }
    </style>
</body>
</html>
