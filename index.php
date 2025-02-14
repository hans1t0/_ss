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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --bg-color: #f8f9fa;
            --text-color: #2c3e50;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .header-banner {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 2rem 0;
            margin-bottom: 2rem;
            color: white;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-title {
            color: var(--text-color);
            border-bottom: 3px solid var(--primary-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.25);
        }

        .jugador-form {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(0,0,0,0.1);
            position: relative;
        }

        #add-jugador {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }

        .remove-jugador {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-success {
            transition: all 0.3s ease;
        }

        .form-section {
            position: relative;
            padding: 2rem;
            margin-bottom: 2rem;
            background: white;
            border-radius: 1rem;
        }

        .form-section::before {
            content: '';
            position: absolute;
            left: 0;
            top: 1rem;
            bottom: 1rem;
            width: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .step-indicator {
            display: flex;
            margin-bottom: 2rem;
            justify-content: center;
            gap: 1rem;
        }

        .step {
            padding: 0.5rem 1rem;
            background: rgba(52,152,219,0.1);
            border-radius: 2rem;
            color: var(--primary-color);
            font-weight: 500;
        }

        .step.active {
            background: var(--primary-color);
            color: white;
        }

        .alert-info {
            background-color: rgba(52,152,219,0.1);
            border: none;
            border-radius: 1rem;
            padding: 1.5rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .form-section {
                padding: 1rem;
            }

            #add-jugador {
                width: 100%;
                margin-top: 1rem;
            }

            .card-title {
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }

            .jugador-form {
                padding: 1rem;
            }

            body {
                display: flex;
                flex-direction: column;
            }

            .container {
                order: 0;
            }

            #formulario-padre {
                order: 1;
            }

            #jugadores-container {
                order: 2;
            }
        }
    </style>
</head>
<body>
    <div class="header-banner">
        <div class="container text-center">
            <img src="logo.png" alt="Racing Playa San Juan" class="mb-3" style="max-height: 80px;">
            <h1 class="display-4 mb-0">Campus de Fútbol</h1>
            <p class="lead">Racing Playa San Juan - Verano 2024</p>
        </div>
    </div>

    <div class="container">
        <!-- Eliminar la sección de pasos -->
        
        <div class="card">
            <div class="card-body">
                <form id="inscripcion-form" action="process.php" method="post" class="needs-validation" novalidate>
                    <!-- Datos del Padre/Tutor -->
                    <div class="mb-4" id="formulario-padre">
                        <h3 class="card-title mb-3">Datos del Padre/Tutor</h3>
                        <div class="row g-3">
                            <?php 
                            // Generar campos del padre usando el template
                            echo generarCamposPadre($campos_padre);
                            ?>
                        </div>
                    </div>

                    <!-- Contenedor de Jugadores -->
                    <div id="jugadores-container">
                        <div class="jugador-form mb-4" data-jugador-id="1">
                            <h3 class="card-title mb-3">Datos del Jugador</h3>
                            <div class="row g-3">
                                <?php
                                foreach ($campos_jugador as $nombre => $campo) {
                                    echo '<div class="col-md-6">';
                                    echo generarCampo($nombre, $campo, '', '_1');
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Añadir Hermano -->
                    <div class="text-end mb-4">
                        <button type="button" class="btn btn-outline-primary" id="add-jugador">
                            <i class="fas fa-plus"></i> Añadir hermano
                        </button>
                    </div>

                    <!-- Consentimiento -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="consentimiento" required>
                            <label class="form-check-label">
                                Acepto el tratamiento de los datos proporcionados
                            </label>
                        </div>
                    </div>

                    <!-- Información de Pago -->
                    <div class="alert alert-info mb-4" id="info_pago">
                        <strong>INFORMACIÓN DE PAGO</strong><br>
                        <span id="info_transferencia" style="display: none;">
                            Se realizará el cargo en la cuenta bancaria proporcionada.<br>
                            Los datos bancarios serán tratados de forma segura y confidencial.
                        </span>
                        <span id="info_coordinador" style="display: none;">
                            El pago se realizará directamente al coordinador.<br>
                            Por favor, contacte con el coordinador para acordar el momento del pago.
                        </span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">Enviar Inscripción</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        let jugadorCount = 1;

        // Función para generar el HTML de un nuevo jugador usando los datos del template
        function generarFormularioHermano(numero) {
            return `
                <div class="jugador-form mb-4" data-jugador-id="${numero}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="card-title">Datos del Hermano ${numero}</h3>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-jugador">
                            <i class="fas fa-times"></i> Eliminar
                        </button>
                    </div>
                    <div class="row g-3">
                        <?php
                        foreach ($campos_jugador as $nombre => $campo) {
                            echo '<div class="col-md-6">';
                            echo generarCampo($nombre, $campo, '', "_${numero}");
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            `;
        }

        // Inicializar el estado del formulario de pago
        const metodoPago = $('#metodo_pago').val(); // Cambiado el ID
        $('#cuenta_bancaria_container').toggle(metodoPago === 'T');
        $('#info_transferencia').toggle(metodoPago === 'T');
        $('#info_coordinador').toggle(metodoPago === 'C');

        // Gestión del método de pago
        $('#metodo_pago').change(function() { // Cambiado el ID
            const metodoPago = $(this).val();
            $('#cuenta_bancaria_container').toggle(metodoPago === 'T');
            $('#info_transferencia').toggle(metodoPago === 'T');
            $('#info_coordinador').toggle(metodoPago === 'C');
        });

        $('#add-jugador').on('click', function() {
            jugadorCount++;
            
            // Clonar el primer formulario de jugador
            const newForm = $('.jugador-form:first').clone();
            
            // Actualizar el título
            newForm.find('.card-title').text(`Datos del Hermano ${jugadorCount}`);
            
            // Actualizar nombres e IDs de los campos
            newForm.find('input, select, textarea').each(function() {
                const $input = $(this);
                const oldName = $input.attr('name');
                const oldId = $input.attr('id');
                
                if (oldName) {
                    const baseName = oldName.replace('_1', '');
                    const newName = `${baseName}_${jugadorCount}`;
                    
                    $input.attr('name', newName);
                    $input.attr('id', newName);
                    $input.val(''); // Limpiar valor
                    
                    // Actualizar labels
                    newForm.find(`label[for="${oldId}"]`).attr('for', newName);
                }
            });

            // Manejar específicamente los radio buttons
            newForm.find('input[type="radio"]').each(function() {
                const $radio = $(this);
                const oldName = $radio.attr('name');
                if (oldName) {
                    const baseName = oldName.split('_1')[0];
                    const newName = `${baseName}_${jugadorCount}`;
                    $radio.attr('name', newName);
                    $radio.prop('checked', false);
                }
            });

            // Añadir botón eliminar
            newForm.prepend(`
                <div class="text-end mb-3">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-jugador">
                        <i class="fas fa-times"></i> Eliminar
                    </button>
                </div>
            `);

            // Añadir el nuevo formulario con animación
            newForm.hide()
                  .appendTo('#jugadores-container')
                  .slideDown(300);
        });

        // Manejador para eliminar jugador
        $(document).on('click', '.remove-jugador', function() {
            const $form = $(this).closest('.jugador-form');
            $form.slideUp(300, function() {
                $(this).remove();
                jugadorCount--;
                
                // Actualizar títulos de los hermanos
                $('.jugador-form').each(function(index) {
                    const title = index === 0 ? 'Datos del Jugador' : `Datos del Hermano ${index + 1}`;
                    $(this).find('.card-title').text(title);
                });
            });
        });

        // Envío simple del formulario sin limpiar datos
        $('#inscripcion-form').submit(function(e) {
            e.preventDefault();
            const $form = $(this);
            
            // No limpiar el formulario antes del envío
            $.ajax({
                type: 'POST',
                url: 'process.php',
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $form.find('button[type="submit"]')
                        .prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm"></span> Enviando...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.href = response.redirect_url;
                    } else {
                        alert(response.message || 'Error en el proceso');
                    }
                },
                error: function() {
                    alert('Error en el proceso de registro');
                },
                complete: function() {
                    $form.find('button[type="submit"]')
                        .prop('disabled', false)
                        .html('Enviar Inscripción');
                }
            });
        });

        // Add smooth scrolling
        $('html, body').animate({
            scrollTop: $('.alert-danger').offset().top - 100
        }, 1000);

        // Add animation when adding new player
        $('#add-jugador').click(function() {
            const newForm = $('.jugador-form:first').clone();
            newForm.hide().appendTo('#jugadores-container').slideDown(300);
        });

        // Add loading animation
        $('#inscripcion-form').submit(function() {
            $(this).find('button[type="submit"]')
                .prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
        });

        // Add tooltip for help texts
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Actualizar la función de progress steps
        function updateProgress() {
            const totalSteps = 3;
            const currentStep = 2; // Cambia según la página actual
            
            $('.step').each(function(index) {
                if (index < currentStep - 1) {
                    $(this).addClass('completed').removeClass('active');
                } else if (index === currentStep - 1) {
                    $(this).addClass('active').removeClass('completed');
                } else {
                    $(this).removeClass('completed active');
                }
            });
        }
    });
    </script>
</body>
</html>