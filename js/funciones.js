$(document).ready(function () {
    // Validación del formulario
    const form = $('#inscripcionForm');
    if (form.length) {  // Verificar que el formulario existe
        form.on('submit', function (e) {
            e.preventDefault();

            // Validar campos requeridos
            let isValid = true;
            form.find('[required]').each(function () {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                mostrarError('Por favor, complete todos los campos requeridos');
                return false;
            }

            // Procesar el formulario
            $.ajax({
                url: 'process2.php',
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        window.location.href = response.redirect_url;
                    } else {
                        mostrarError(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    mostrarError('Error en el servidor: ' + error);
                }
            });
        });
    }

    // Manejar clics en el botón de agregar hermano
    $('#agregarHermano').on('click', function (e) {
        e.preventDefault();
        const hermanoCount = $('.hermano-container').length + 1;

        if (hermanoCount <= 3) {
            const nuevoHermano = $('#plantilla-hermano').clone()
                .removeAttr('id')
                .addClass('hermano-container')
                .attr('data-hermano', hermanoCount);

            // Actualizar IDs y names
            nuevoHermano.find('[id]').each(function () {
                const oldId = $(this).attr('id');
                const newId = oldId.replace('_1', '_' + hermanoCount);
                $(this).attr('id', newId)
                    .attr('name', newId);
            });

            // Actualizar labels
            nuevoHermano.find('label[for]').each(function () {
                const oldFor = $(this).attr('for');
                const newFor = oldFor.replace('_1', '_' + hermanoCount);
                $(this).attr('for', newFor);
            });

            // Mostrar el contenedor y agregarlo al formulario
            nuevoHermano.removeClass('d-none')
                .insertBefore('#agregarHermanoContainer');

            // Actualizar contador de hermanos
            $('#totalHermanos').val(hermanoCount);

            if (hermanoCount === 3) {
                $(this).hide(); // Ocultar botón si se alcanza el máximo
            }
        }
    });

    // Función para mostrar errores
    function mostrarError(mensaje) {
        const alertDiv = $('<div>')
            .addClass('alert alert-danger alert-dismissible fade show')
            .attr('role', 'alert')
            .html(`
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `);

        // Insertar el mensaje al principio del formulario
        if (form.length) {
            form.prepend(alertDiv);

            // Scroll suave hacia el mensaje de error
            $('html, body').animate({
                scrollTop: alertDiv.offset().top - 100
            }, 500);
        }
    }

    // Inicialización de elementos de la interfaz
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        language: 'es'
    });

    $('[data-toggle="tooltip"]').tooltip();

    // Manejar cambios en campos requeridos
    $('[required]').on('change', function () {
        if ($(this).val()) {
            $(this).removeClass('is-invalid');
        }
    });
});
