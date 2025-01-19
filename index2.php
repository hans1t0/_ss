<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Familiar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="mb-4 text-center">Registro Familiar</h1>

    <!-- Sección de Retroalimentación Mejorada -->
    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
            <?php if ($_GET['status'] === 'success'): ?>
                <strong>¡Éxito!</strong> Su registro se completó correctamente.
            <?php else: ?>
                <strong>Error:</strong> <?= htmlspecialchars($_GET['message'] ?? 'Hubo un problema al procesar su registro') ?>
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form id="family-form" action="process.php" method="post" class="needs-validation" novalidate>
        <!-- Sección Padre -->
        <div class="form-section parent-section card p-3 mb-4">
            <legend>Datos Padre / Madre / Tutor:</legend>
            <div class="form-group mb-3">
                <input type="text" class="form-control" placeholder="Nombre y Apellidos" name="pnombre" id="pnombre" required />
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Teléfono 1" name="telefonos" id="telefonos" required />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Teléfono 2" name="telefonos2" id="telefonos2" />
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <input type="email" class="form-control" placeholder="Correo electrónico" name="email" id="email" required />
                    <span id="check-e"></span>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="DNI/NIE/NIF" name="dni" id="dni" required />
                </div>
            </div>
        </div>

        <!-- Sección Niños -->
        <div class="form-section card p-3 mb-4">
            <h3 class="mb-3">Información de Niños</h3>
            <div id="children-container" class="mb-3"></div>
            <button type="button" id="add-child-btn" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Niño
            </button>
        </div>

        <!-- Talleres y Servicios -->
        <div class="form-section card p-3 mb-4">
            <legend>Selección de Servicios</legend>
            <div class="row">
                <div class="form-group col-md-3">
                    <label>Socio Ampa:</label>
                    <select class="form-control" id="ampa" name="ampa" required>
                        <option value="" selected disabled>Selecciona una opción</option>
                        <option value="Si">Socio AMPA</option>
                        <option value="No">NO Socio AMPA</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Semanas:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="sem1" id="semana1" name="sem[]">
                        <label class="form-check-label" for="semana1">1ª semana (1-7 Julio)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="sem2" id="semana2" name="sem[]">
                        <label class="form-check-label" for="semana2">2ª semana (8-14 Julio)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="sem3" id="semana3" name="sem[]">
                        <label class="form-check-label" for="semana3">3ª semana (15-21 Julio)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="sem4" id="semana4" name="sem[]">
                        <label class="form-check-label" for="semana4">4ª semana (22-28 Julio)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="sem5" id="semana5" name="sem[]">
                        <label class="form-check-label" for="semana5">5ª semana (29-31 Julio)</label>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label>Guardería Matinal</label>
                    <select class="form-control" id="guarderia" name="guarderia" required>
                        <option value="g0" selected>Sin Guardería</option>
                        <option value="g1">Guardería (7:30 - 9:00)</option>
                        <option value="g2">Guardería (8:00 - 9:00)</option>
                        <option value="g3">Guardería (8:30 - 9:00)</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Comedor</label>
                    <select class="form-control" id="comedor" name="comedor" required>
                        <option value="no_com" selected>Sin Comedor</option>
                        <option value="si">Con Comedor</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Alertas y Observaciones -->
        <div class="alert alert-danger mb-4">
            <strong>5ª SEMANA</strong><br />
            <p>Los días <strong>29, 30 y 31 de julio</strong> se podrán contratar mediante la modalidad de días sueltos
            (<b>15€/día</b> para el horario de talleres de 9:00 a 14:00 h.) sólo para aquellas familias interesadas que hayan contratado,
            al menos, una de las semanas previas del servicio.</p>
        </div>

        <!-- Observaciones -->
        <div class="form-group mb-4">
            <input type="text" class="form-control" placeholder="Observaciones" name="observaciones" id="observaciones" />
        </div>

        <!-- Consentimientos -->
        <div class="form-section card p-3 mb-4">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="gdpr" name="con[]" value="gdpr" required>
                <label class="form-check-label" for="gdpr">Tratamiento de datos bajo GDPR</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="con_img" name="con[]" value="imagen" required>
                <label class="form-check-label" for="con_img">Uso de la imagen</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="con_email" name="con[]" value="email" required>
                <label class="form-check-label" for="con_email">Guardar email para gestión y ofertas</label>
            </div>
        </div>

        <!-- Información de Pago -->
        <div class="alert alert-success mb-4">
            <strong>Modalidad de pago:</strong><br />
            Envío de justificante de transferencia bancaria a inscripciones@educap.es / Pago Transferencia Bancaria de la modalidad elegida.<br />
            <b>Titular: EDUCAP Serveis d'Oci S.L. · Concepto: EV24+Nombre alumno+Colegio · IBAN: ES30 3058 2519 4927 2000 6473</b>
        </div>

        <!-- Botón Submit -->
        <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> Enviar Registro
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function () {
    let childIndex = 0;

    function addChild() {
        const childHtml = `
            <div class="child-section card p-3 mb-3" id="child-${childIndex}">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm remove-child-btn" 
                            data-child-index="${childIndex}">
                        <i class="fas fa-times"></i> Eliminar
                    </button>
                </div>
                <fieldset>
                    <legend>Datos del niñ@</legend>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" placeholder="Nombre" 
                                   name="nombre[]" required />
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" placeholder="Apellidos" 
                                   name="apellidos[]" required />
                        </div>
                        <div class="form-group col-md-4">
                            <input type="number" class="form-control" placeholder="Edad" 
                                   name="edad[]" required min="1" />
                        </div>
                        <div class="form-group col-md-4">
                            <select class="form-control" name="curso[]" required>
                                <option value="" selected disabled>Selecciona curso</option>
                                <option value="1inf">1 INF</option>
                                <option value="2inf">2 INF</option>
                                <option value="3inf">3 INF</option>
                                <option value="1prim">1 PRIM</option>
                                <option value="2prim">2 PRIM</option>
                                <option value="3prim">3 PRIM</option>
                                <option value="4prim">4 PRIM</option>
                                <option value="5prim">5 PRIM</option>
                                <option value="6prim">6 PRIM</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <select class="form-control" name="colegio[]" required>
                                <option value="" selected disabled>Selecciona tu colegio</option>
                                <option value="alm">La Almadraba</option>
                                <option value="con">La Condomina</option>
                                <option value="far">El Faro</option>
                                <option value="vor">Voramar</option>
                                <option value="cb">Costa Blanca</option>
                                <option value="med">Mediterráneo</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <input type="text" class="form-control" 
                               placeholder="Enfermedades / Alergias" name="lesiones[]" />
                    </div>
                    <div class="form-group mt-3">
                        <div class="form-control">
                            <label>Nivel de natación</label>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" 
                                           name="natacion[${childIndex}]" value="bajo" required> Bajo
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" 
                                           name="natacion[${childIndex}]" value="medio"> Medio
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" 
                                           name="natacion[${childIndex}]" value="alto"> Alto
                                </label>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        `;
        $('#children-container').append(childHtml);
        childIndex++;
    }

    $('#add-child-btn').click(addChild);

    $(document).on('click', '.remove-child-btn', function() {
        const container = $('#children-container');
        if (container.children().length > 1) {
            $(this).closest('.child-section').remove();
        } else {
            alert('Debe mantener al menos un niño registrado');
        }
    });

    // Form validation
    const form = document.getElementById('family-form');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const childrenCount = $('#children-container').children().length;
        if (childrenCount === 0) {
            event.preventDefault();
            alert('Debe registrar al menos un niño');
        }
        
        form.classList.add('was-validated');
    }, false);

    // Agregar validación del formulario
    $('#family-form').on('submit', function(e) {
        let isValid = true;
        let errorMessage = '';

        // Validar email
        const email = $('#email').val();
        if (email && !isValidEmail(email)) {
            errorMessage += 'El formato del email no es válido\n';
            isValid = false;
        }

        // Validar DNI
        const dni = $('#dni').val();
        if (dni && !isValidDNI(dni)) {
            errorMessage += 'El formato del DNI no es válido\n';
            isValid = false;
        }

        // Validar selección de semanas
        if ($('input[name="sem[]"]:checked').length === 0) {
            errorMessage += 'Debe seleccionar al menos una semana\n';
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
        }
    });

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidDNI(dni) {
        return /^[0-9]{8}[A-Z]$/.test(dni);
    }

    // Validaciones en tiempo real
    const validations = {
        pnombre: {
            regex: /^[A-Za-zÀ-ÿ\s]{3,100}$/,
            message: 'El nombre debe contener solo letras y espacios (mínimo 3 caracteres)'
        },
        dni: {
            regex: /^[0-9]{8}[A-Z]$/,
            message: 'El DNI debe tener 8 números seguidos de una letra mayúscula'
        },
        email: {
            regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            message: 'Introduce un email válido'
        },
        telefonos: {
            regex: /^[0-9]{9}$/,
            message: 'El teléfono debe tener 9 dígitos'
        }
    };

    // Aplicar validaciones en tiempo real
    Object.keys(validations).forEach(fieldId => {
        $(`#${fieldId}`).on('input', function() {
            validateField($(this), validations[fieldId]);
        });
    });

    function validateField($field, validation) {
        const value = $field.val();
        const isValid = validation.regex.test(value);
        
        if (value) {
            if (isValid) {
                $field
                    .removeClass('is-invalid')
                    .addClass('is-valid')
                    .siblings('.invalid-feedback, .valid-feedback')
                    .remove();
                $field.after(`<div class="valid-feedback">Campo válido</div>`);
            } else {
                $field
                    .removeClass('is-valid')
                    .addClass('is-invalid')
                    .siblings('.invalid-feedback, .valid-feedback')
                    .remove();
                $field.after(`<div class="invalid-feedback">${validation.message}</div>`);
            }
        }
    }

    // Validación del formulario completo
    $('#family-form').on('submit', function(e) {
        e.preventDefault();
        let isValid = true;
        let firstError = null;

        // Validar campos del padre/tutor
        Object.keys(validations).forEach(fieldId => {
            const $field = $(`#${fieldId}`);
            const validation = validations[fieldId];
            
            if (!validation.regex.test($field.val())) {
                isValid = false;
                if (!firstError) firstError = $field;
                validateField($field, validation);
            }
        });

        // Validar semanas seleccionadas
        if ($('input[name="sem[]"]:checked').length === 0) {
            isValid = false;
            $('#semanas-error').remove();
            $('.form-check').first().before(
                '<div id="semanas-error" class="alert alert-danger">Debes seleccionar al menos una semana</div>'
            );
            if (!firstError) firstError = $('#semana1');
        }

        // Validar niños
        $('.child-section').each(function() {
            const $child = $(this);
            const $requiredFields = $child.find('[required]');
            
            $requiredFields.each(function() {
                const $field = $(this);
                if (!$field.val()) {
                    isValid = false;
                    $field.addClass('is-invalid');
                    if (!firstError) firstError = $field;
                }
            });
        });

        // Validar consentimientos
        if ($('input[name="con[]"]:checked').length < 3) {
            isValid = false;
            $('#consent-error').remove();
            $('.form-check').last().after(
                '<div id="consent-error" class="alert alert-danger">Debes aceptar todos los consentimientos</div>'
            );
            if (!firstError) firstError = $('#gdpr');
        }

        if (!isValid) {
            firstError.focus();
            return false;
        }

        // Si todo es válido, enviar el formulario mediante AJAX
        $.ajax({
            type: 'POST',
            url: 'process.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Redirigir a la página de éxito
                    window.location.href = 'success.php?id=' + response.inscripcion_id;
                } else {
                    // Mostrar error pero mantener los datos
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error en el proceso de registro');
            }
        });
    });

    // Inicializar con un solo niño
    addChild();

    // Verificar si hay mensaje de éxito en la URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success') {
        $('#family-form')[0].reset();
        $('#children-container').empty();
        addChild();
    }
});
</script>

<style>
.form-section {
    max-width: 100%;
    margin: 0 auto;
}
.child-section {
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
}
.row {
    margin-left: 0;
    margin-right: 0;
}
.form-control {
    width: 100%;
}
.col-md-6, .col-md-4, .col-md-3 {
    padding: 0.5rem;
}
.badge {
    display: none; /* Ocultar los badges de precios */
}
.form-check {
    padding: 8px;
    border-radius: 4px;
    margin-bottom: 5px;
    background-color: #f8f9fa;
}
.form-check:hover {
    background-color: #e9ecef;
}
</style>
</body>
</html>