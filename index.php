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

    <?php if (isset($_GET['status'])): ?>
    <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
        <?php if ($_GET['status'] === 'success'): ?>
            <strong>¡Éxito!</strong> Su inscripción se completó correctamente.
        <?php else: ?>
            <strong>Error:</strong> <?= htmlspecialchars($_GET['message'] ?? 'Hubo un problema al procesar su inscripción') ?>
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
                                <option value="transferencia">Transferencia Bancaria</option>
                                <option value="coordinador">Pago al Coordinador</option>
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
                                       name="hijo_nombre_completo" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="hijo_fecha_nacimiento" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Grupo de Edad</label>
                                <select class="form-control" name="grupo" required>
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
                                        <input class="form-check-input" type="radio" name="sexo" 
                                               value="H" required>
                                        <label class="form-check-label">Hombre</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sexo" 
                                               value="M">
                                        <label class="form-check-label">Mujer</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Demarcación</label>
                                <div class="form-control py-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="demarcacion" 
                                               value="jugador" required>
                                        <label class="form-check-label">Jugador</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="demarcacion" 
                                               value="portero">
                                        <label class="form-check-label">Portero</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Modalidad</label>
                                <select class="form-control" name="modalidad" required>
                                    <option value="" selected disabled>Selecciona Modalidad</option>
                                    <option value="RPSJ">Jugador de RPSJ</option>
                                    <option value="NO_RPSJ">No jugador de RPSJ</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lesiones o Alergias</label>
                                <textarea class="form-control" placeholder="Opcional" 
                                         name="lesiones" rows="1"></textarea>
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

    // Función para clonar formulario de jugador
    function addJugadorForm() {
        jugadorCount++;
        const newForm = $('.jugador-form:first').clone();
        
        // Limpiar valores
        newForm.attr('data-jugador-id', jugadorCount);
        newForm.find('input, select, textarea').val('');
        newForm.find('input[type="radio"]').prop('checked', false);
        newForm.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
        
        // Actualizar nombres de campos
        newForm.find('input, select, textarea').each(function() {
            const oldName = $(this).attr('name');
            if (oldName) {
                $(this).attr('name', oldName + '_' + jugadorCount);
            }
        });
        
        // Actualizar nombres de campos de sexo
        newForm.find('input[name="sexo"]').each(function() {
            $(this).attr('name', 'sexo_' + jugadorCount);
        });

        // Añadir botón eliminar
        const header = newForm.find('.d-flex');
        header.html(`
            <h3 class="card-title">Datos del Hermano ${jugadorCount}</h3>
            <button type="button" class="btn btn-danger btn-sm remove-jugador">
                <i class="fas fa-times"></i> Eliminar
            </button>
        `);
        
        // Añadir al contenedor
        $('#jugadores-container').append(newForm);
        
        // Reinicializar validaciones para el nuevo formulario
        initializeValidations(newForm);
    }

    // Evento añadir jugador
    $('#add-jugador').click(addJugadorForm);

    // Evento eliminar jugador
    $(document).on('click', '.remove-jugador', function() {
        if (jugadorCount > 1) {
            $(this).closest('.jugador-form').remove();
            jugadorCount--;
            
            // Actualizar títulos de los hermanos
            $('.jugador-form').each(function(index) {
                if (index > 0) {
                    $(this).find('.card-title').text(`Datos del Hermano ${index + 1}`);
                }
            });
        }
    });

    // Función para inicializar validaciones en un formulario
    function initializeValidations($form) {
        // Aplicar validaciones a los campos del nuevo formulario
        const formId = $form.data('jugador-id');
        const validations = {
            [`hijo_nombre_completo_${formId}`]: {
                regex: /^[A-Za-zÀ-ÿ\s]{2,100}$/,
                message: 'El nombre completo debe contener solo letras y espacios'
            }
        };

        Object.keys(validations).forEach(fieldName => {
            $form.find(`[name="${fieldName}"]`).on('input', function() {
                validateField($(this), validations[fieldName]);
            });
        });

        // Reinicializar select validations
        $form.find('select').on('change', function() {
            if (this.value) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });
    }

    // Validación del formulario y envío AJAX
    $('#inscripcion-form').on('submit', function(e) {
        e.preventDefault();
        
        if (!this.checkValidity()) {
            $(this).addClass('was-validated');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: 'process.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Redireccionar a la página de éxito
                    window.location.href = response.redirect_url;
                } else {
                    // Mostrar error
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error en el proceso de registro');
            }
        });
    });

    // Validación de email
    $('input[type="email"]').on('input', function() {
        const email = $(this).val();
        const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        
        if (email && !validEmail) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Validación de teléfono
    $('input[type="tel"]').on('input', function() {
        const phone = $(this).val();
        const validPhone = /^[0-9]{9}$/.test(phone);
        
        if (phone && !validPhone) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Validaciones básicas
    const validations = {
        padre_dni: {
            regex: /^[0-9]{8}[A-Z]$/,
            message: 'DNI debe tener 8 números y una letra mayúscula'
        },
        padre_email: {
            regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            message: 'Introduce un email válido'
        },
        cuenta_bancaria: {
            regex: /^ES[0-9]{22}$/,
            message: 'IBAN debe comenzar con ES seguido de 22 números'
        },
        padre_telefono: {
            regex: /^[0-9]{9}$/,
            message: 'El teléfono debe tener 9 dígitos'
        }
    };

    // Aplicar validaciones en tiempo real
    Object.keys(validations).forEach(fieldName => {
        $(`[name="${fieldName}"]`).on('input', function() {
            const validation = validations[fieldName];
            if (this.value && !validation.regex.test(this.value)) {
                $(this).addClass('is-invalid')
                       .removeClass('is-valid');
                if (!$(this).next('.invalid-feedback').length) {
                    $(this).after(`<div class="invalid-feedback">${validation.message}</div>`);
                }
            } else {
                $(this).removeClass('is-invalid')
                       .addClass('is-valid')
                       .next('.invalid-feedback').remove();
            }
        });
    });

    // Validación para grupo y modalidad
    $('select[name="grupo"], select[name="modalidad"]').on('change', function() {
        if (this.value) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });

    // Gestión del método de pago
    $('#metodo_pago').on('change', function() {
        const metodoPago = $(this).val();
        const $cuentaContainer = $('#cuenta_bancaria_container');
        const $cuentaBancaria = $('#cuenta_bancaria');
        const $infoTransferencia = $('#info_transferencia');
        const $infoCoordinador = $('#info_coordinador');
        
        if (metodoPago === 'transferencia') {
            $cuentaContainer.slideDown();
            $cuentaBancaria.prop('required', true);
            $infoTransferencia.show();
            $infoCoordinador.hide();
        } else if (metodoPago === 'coordinador') {
            $cuentaContainer.slideUp();
            $cuentaBancaria.prop('required', false);
            $infoTransferencia.hide();
            $infoCoordinador.show();
        }
    });

    // Ocultar campo de cuenta bancaria inicialmente
    $('#cuenta_bancaria_container').hide();
    $('#cuenta_bancaria').prop('required', false);
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
