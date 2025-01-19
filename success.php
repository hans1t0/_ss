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
                        
                        <?php
                        if (isset($_GET['id'])) {
                            $inscripcionId = htmlspecialchars($_GET['id']);
                            echo "<p class='lead mb-4'>Número de inscripción: #$inscripcionId</p>";
                        }
                        ?>

                        <div class="alert alert-info mb-4">
                            <h4 class="alert-heading mb-3">Próximos Pasos</h4>
                            <div id="info_pago" class="text-start">
                                <?php
                                // Obtener información del método de pago de la sesión o base de datos
                                $metodoPago = $_GET['metodo'] ?? 'transferencia';
                                
                                if ($metodoPago === 'transferencia') {
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
