<?php
session_start();

// Mensaje simple de error/éxito
$status = $_GET['status'] ?? null;
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus de Fútbol | Racing Playa San Juan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="text-center mb-4">Inscripción Campus de Fútbol</h1>

    <?php if (isset($status)): ?>
    <div class="alert alert-<?= $status === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
        <?php if ($status === 'success'): ?>
            <strong>¡Éxito!</strong> Su inscripción se completó correctamente.
        <?php else: ?>
            <strong>Error:</strong> <?= htmlspecialchars($message) ?>
        <?php endif; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form id="inscripcion-form" action="process.php" method="post" class="needs-validation" novalidate>
                <!-- Datos del Padre/Tutor -->
                <div class="mb-4">
                    <h3 class="card-title mb-3">Datos del Padre/Tutor</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Nombre del Padre/Tutor" 
                                   name="padre_nombre" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="DNI" name="padre_dni" required>
                        </div>
                        <div class="col-md-6">
                            <input type="tel" class="form-control" placeholder="Teléfono" 
                                   name="padre_telefono" required 
                                   pattern="[0-9]{9}"
                                   title="Número de teléfono (9 dígitos)">
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control" placeholder="Correo Electrónico" 
                                   name="padre_email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Forma de Pago</label>
                            <select class="form-control" name="metodo_pago" id="metodo_pago" required>
                                <option value="" selected disabled>Seleccione método de pago</option>
                                <option value="T">Transferencia Bancaria</option>
                                <option value="C">Pago al Coordinador</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="cuenta_bancaria_container">
                            <label class="form-label">IBAN para Cargo</label>
                            <input type="text" class="form-control" placeholder="ES + 22 dígitos" 
                                   name="cuenta_bancaria" id="cuenta_bancaria"
                                   pattern="ES[0-9]{2}[0-9]{20}"
                                   title="IBAN español: ES seguido de 22 números">
                            <div class="form-text">Formato: ES seguido de 22 números (ejemplo: ES6621000418401234567891)</div>
                        </div>
                    </div>
                </div>

                <!-- Contenedor de Jugadores -->
                <div id="jugadores-container">
                    <div class="jugador-form mb-4" data-jugador-id="1">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="card-title">Datos del Jugador</h3>
                            <button type="button" class="btn btn-success btn-sm" id="add-jugador">
                                <i class="fas fa-plus"></i> Añadir Hermano
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <input type="text" class="form-control" placeholder="Nombre y Apellidos del jugador" 
                                       name="hijo_nombre_completo_1" required>  <!-- Añadido _1 -->
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="hijo_fecha_nacimiento_1" required>  <!-- Añadido _1 -->
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Grupo de Edad</label>
                                <select class="form-control" name="grupo_1" required>  <!-- Añadido _1 -->
                                    <option value="" selected disabled>Selecciona Grupo</option>
                                    <option value="Querubin">Querubines (5 años)</option>
                                    <option value="Prebenjamin">Prebenjamin (6-7 años)</option>
                                    <option value="Benjamin">Benjamines (8-9 años)</option>
                                    <option value="Alevin">Alevines (10-11 años)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sexo</label>
                                <div class="form-control py-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sexo_1" 
                                               id="sexo_h_1" value="H" required>
                                        <label class="form-check-label" for="sexo_h_1">Hombre</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sexo_1" 
                                               id="sexo_m_1" value="M" required>
                                        <label class="form-check-label" for="sexo_m_1">Mujer</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Demarcación</label>
                                <div class="form-control py-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="demarcacion_1" 
                                               id="demarcacion_j_1" value="jugador" required>
                                        <label class="form-check-label" for="demarcacion_j_1">Jugador campo</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="demarcacion_1" 
                                               id="demarcacion_p_1" value="portero" required>
                                        <label class="form-check-label" for="demarcacion_p_1">Portero</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Modalidad</label>
                                <select class="form-control" name="modalidad_1" required>  <!-- Añadido _1 -->
                                    <option value="" selected disabled>Selecciona Modalidad</option>
                                    <option value="RPSJ">Jugador de RPSJ</option>
                                    <option value="NO_RPSJ">No jugador de RPSJ</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lesiones o Alergias</label>
                                <textarea class="form-control" placeholder="Opcional" 
                                         name="lesiones_1" rows="1"></textarea>  <!-- Añadido _1 -->
                            </div>
                        </div>
                    </div>
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

    $('#add-jugador').click(function() {
        jugadorCount++;
        const newForm = $('.jugador-form:first').clone();
        
        // Actualizar todos los inputs, selects y textareas
        newForm.find('input:not([type="radio"]), select, textarea').each(function() {
            const $input = $(this);
            const oldName = $input.attr('name');
            if (oldName && !oldName.includes('padre_')) {
                const newName = oldName.replace('_1', `_${jugadorCount}`);
                $input.attr('name', newName);
                $input.val('');
            }
        });

        // Actualizar específicamente los radio buttons
        // Sexo
        newForm.find('input[name="sexo_1"]').each(function() {
            const $radio = $(this);
            const oldId = $radio.attr('id');
            const newId = oldId.replace('_1', `_${jugadorCount}`);
            
            $radio.attr('name', `sexo_${jugadorCount}`)
                  .attr('id', newId)
                  .prop('checked', false);
            
            $radio.next('label').attr('for', newId);
        });

        // Demarcación
        newForm.find('input[name="demarcacion_1"]').each(function() {
            const $radio = $(this);
            const oldId = $radio.attr('id');
            const newId = oldId.replace('_1', `_${jugadorCount}`);
            
            $radio.attr('name', `demarcacion_${jugadorCount}`)
                  .attr('id', newId)
                  .prop('checked', false);
            
            $radio.next('label').attr('for', newId);
        });
        
        // Actualizar el título y añadir botón eliminar
        const header = newForm.find('.d-flex');
        header.html(`
            <h3 class="card-title">Datos del Hermano ${jugadorCount}</h3>
            <button type="button" class="btn btn-danger btn-sm remove-jugador">
                <i class="fas fa-times"></i> Eliminar
            </button>
        `);
        
        $('#jugadores-container').append(newForm);
    });

    // Resto del código existente para eliminar y gestión del formulario
    $(document).on('click', '.remove-jugador', function() {
        if (jugadorCount > 1) {
            $(this).closest('.jugador-form').remove();
            jugadorCount--;
            
            // Actualizar números de hermanos
            $('.jugador-form').each(function(index) {
                if (index > 0) {
                    $(this).find('.card-title').text(`Datos del Hermano ${index + 1}`);
                }
            });
        }
    });

    // Gestión básica del método de pago
    $('#metodo_pago').change(function() {
        const metodoPago = $(this).val();
        $('#cuenta_bancaria_container').toggle(metodoPago === 'T');
        $('#info_transferencia').toggle(metodoPago === 'T');
        $('#info_coordinador').toggle(metodoPago === 'C');
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
    
});
</script>

<style>
.card {
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.card-title {
    color: #2c3e50;
    border-bottom: 2px solid #3498db;
    padding-bottom: 0.5rem;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.25);
}

.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
}

.btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
}

.alert-info {
    background-color: #f8f9fa;
    border-left: 4px solid #3498db;
}

.jugador-form {
    border: 1px solid #dee2e6;
    padding: 1.5rem;
    border-radius: 8px;
    background-color: #fff;
    margin-bottom: 1.5rem;
}

.remove-jugador {
    margin-left: 1rem;
}

#add-jugador {
    white-space: nowrap;
}

#cuenta_bancaria_container {
    transition: all 0.3s ease-in-out;
}

.alert-info span {
    display: block;
    margin-top: 0.5rem;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.3rem;
    color: #2c3e50;
}

.row.g-3 {
    margin-bottom: -0.5rem;
}

.form-control {
    margin-bottom: 0.5rem;
}

.form-check-inline {
    margin-right: 2rem;
}

.jugador-form .row {
    align-items: flex-end;
}
</style>
</body>
</html>
