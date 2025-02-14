<?php
session_start();

// Mensaje simple de error/éxito
$status = $_GET['status'] ?? null;
$message = $_GET['message'] ?? null;

// Cargar primero el template del usuario que contiene la función generarCampo
require_once 'templates/template_usuario.php';
// Luego cargar el template del padre que usa esa función
require_once 'templates/template_padre.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus de Fútbol | Racing Playa San Juan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="funciones.js"></script>
</head>
<body class="bg-light text-dark">
    <div class="header-banner text-center py-8 bg-cover bg-center mb-4" style="background-image: url('futbol-banner.jpg');">
        <div class="container mx-auto">
            <img src="images/cabecera.png" alt="Cabecera" class="img-fluid mb-4">
            <img src="logo.png" alt="Racing Playa San Juan" class="img-fluid mb-4">
            <h1 class="display-4 text-white">Campus de Fútbol</h1>
        </div>
    </div>

    <div class="container">
        <?php if ($status && $message): ?>
            <div class="alert alert-<?php echo $status; ?> p-4 mb-4">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>        
        <div class="card shadow-lg">
            <div class="card-body">
                <form id="inscripcion-form" action="process2.php" method="post" class="needs-validation" novalidate>
                    <!-- Información de Precios -->
                    <div class="mb-4">
                        <div class="alert alert-info">
                            <h5 class="alert-heading"><i class="fas fa-euro-sign me-2"></i>Información de Precios</h5>
                            <p class="mb-0">Cuota Campus: 90€</p>
                            <p class="mb-0">Descuento Familiar:</p>
                            <ul>
                                <li>5€ segundo hij@</li>
                                <li>10€ tercer hij@</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Datos del Padre/Tutor -->
                    <div class="mb-4" id="padre-container">
                        <div class="padre-form">
                            <h3 class="card-title"><i class="fas fa-user me-2"></i>Datos del Padre/Tutor</h3>
                            
                            <!-- Nombre y DNI en la misma fila -->
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <?php echo generarCampo('padre_nombre', $campos_padre['padre_nombre']); ?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo generarCampo('padre_dni', $campos_padre['padre_dni']); ?>
                                </div>
                            </div>

                            <!-- Email y Teléfono en la misma fila -->
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <?php echo generarCampo('padre_email', $campos_padre['padre_email']); ?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo generarCampo('padre_telefono', $campos_padre['padre_telefono']); ?>
                                </div>
                            </div>

                            <!-- Método de pago e información -->
                            <div class="row g-3 metodo-pago-container">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <?php echo generarCampo('metodo_pago', $campos_padre['metodo_pago']); ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="h-100">
                                        <?php echo generarInformacionPago(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenedor de Jugadores -->
                    <div id="jugadores-container" class="mb-xl">
                        <div class="jugador-form" data-jugador-id="1">
                            <h3 class="card-title"><i class="fas fa-child me-2"></i>Datos del Jugador</h3>
                            <div class="row g-4">
                                <?php
                                foreach ($campos_jugador as $nombre => $campo) {
                                    echo '<div class="col-md-6 form-group">';
                                    echo generarCampo($nombre, $campo, '', '_1');
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Añadir Hermano -->
                    <div class="text-end mb-lg">
                        <button type="button" class="btn btn-outline-primary" id="add-jugador">
                            <i class="fas fa-plus me-2"></i>Añadir hermano
                        </button>
                    </div>

                    <!-- Consentimiento -->
                    <div class="mb-lg">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="consentimiento" required>
                            <label class="form-check-label">
                                <i class="fas fa-check-circle me-2"></i>Acepto el tratamiento de los datos proporcionados
                            </label>
                            <div class="invalid-feedback">
                                Debes aceptar el tratamiento de los datos.
                            </div>
                        </div>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="consentimiento_imagen" required>
                            <label class="form-check-label">
                                <i class="fas fa-check-circle me-2"></i>Acepto el tratamiento de las imágenes proporcionadas
                            </label>
                            <div class="invalid-feedback">
                                Debes aceptar el tratamiento de las imágenes.
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-paper-plane me-2"></i>Enviar Inscripción
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>