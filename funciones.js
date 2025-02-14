$(document).ready(function () {
    let jugadorCount = 1;
    const MAX_JUGADORES = 3;

    // Función para manejar errores
    function mostrarError(mensaje) {
        if ($('.alert-danger').length) {
            $('.alert-danger').remove();
        }

        const alertDiv = $('<div>')
            .addClass('alert alert-danger alert-dismissible fade show')
            .attr('role', 'alert')
            .html(`
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `);

        // Insertar el mensaje al principio del formulario
        $('#inscripcion-form').prepend(alertDiv);

        // Scroll suave hacia el mensaje solo si existe
        if (alertDiv.length) {
            $('html, body').animate({
                scrollTop: alertDiv.offset().top - 20
            }, 500);
        }
    }

    // Función para actualizar la información de pago
    function actualizarInfoPago() {
        const metodoPago = $('#metodo_pago').val();
        console.log('Método de pago seleccionado:', metodoPago); // Debug
        const infoPagoContainer = $('.info-pago');

        // Mostrar el contenedor principal
        infoPagoContainer.toggle(metodoPago !== '');

        // Actualizar información de pago
        if (metodoPago === 'transferencia') {
            $('#info_transferencia').show();
            $('#info_coordinador').hide();
            $('#info_pago').removeClass('alert-warning').addClass('alert-info');
        } else if (metodoPago === 'coordinador') {
            $('#info_transferencia').hide();
            $('#info_coordinador').show();
            $('#info_pago').removeClass('alert-info').addClass('alert-warning');
        } else {
            $('#info_transferencia, #info_coordinador').hide();
        }
    }

    // Evento cambio método de pago
    $('#metodo_pago').on('change', function () {
        actualizarInfoPago();
    }).trigger('change'); // Ejecutar al cargar

    // Agregar hermano
    $('#add-jugador').on('click', function () {
        if (jugadorCount >= MAX_JUGADORES) {
            mostrarError('No se pueden agregar más de 3 jugadores');
            return;
        }

        jugadorCount++;
        const nuevoJugador = $('#jugador-template .jugador-form').clone()
            .attr('data-jugador-id', jugadorCount);

        // Actualizar IDs y names
        nuevoJugador.find('[id]').each(function () {
            const oldId = $(this).attr('id');
            $(this).attr('id', oldId.replace('_N', '_' + jugadorCount))
                .attr('name', oldId.replace('_N', '_' + jugadorCount));
        });

        // Actualizar labels
        nuevoJugador.find('label[for]').each(function () {
            const oldFor = $(this).attr('for');
            $(this).attr('for', oldFor.replace('_N', '_' + jugadorCount));
        });

        // Limpiar valores
        nuevoJugador.find('input, select').val('');

        // Actualizar título
        nuevoJugador.find('h3').text('Datos del Jugador ' + jugadorCount);

        // Agregar al contenedor
        $('#jugadores-container').append(nuevoJugador);

        if (jugadorCount === MAX_JUGADORES) {
            $(this).prop('disabled', true);
        }
    });

    // Eliminar hermano
    $(document).on('click', '.remove-jugador', function () {
        $(this).closest('.jugador-form').remove();
        jugadorCount--;
        $('#add-jugador').prop('disabled', false);
    });

    // Validación del formulario
    $('#inscripcion-form').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                console.log('Respuesta:', response);
                if (response.status === 'success' && response.redirect_url) {
                    window.location.href = response.redirect_url;
                } else {
                    mostrarError(response.message || 'Error en el procesamiento');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error AJAX:', { xhr, status, error });
                let mensaje = 'Error en el servidor';
                try {
                    const response = JSON.parse(xhr.responseText);
                    mensaje = response.message || mensaje;
                } catch (e) {
                    console.error('Error parsing:', e);
                }
                mostrarError(mensaje);
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Inicialización
    actualizarInfoPago();

    // Desplazamiento suave al hacer clic en los enlaces del menú
    $(".navbar-nav a").on('click', function (event) {
        if (this.hash !== "") {
            event.preventDefault();

            var hash = this.hash;

            $('html, body').animate({
                scrollTop: $(hash).offset().top - 70
            }, 800, function () {
                window.location.hash = hash;
            });
        }
    });
});
