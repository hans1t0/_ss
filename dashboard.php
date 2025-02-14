<?php
session_start();
require_once 'config/database.php';

try {
    // Estadísticas generales
    $stats = [
        'total_inscripciones' => $pdo->query("SELECT COUNT(*) FROM padres")->fetchColumn(),
        'total_jugadores' => $pdo->query("SELECT COUNT(*) FROM jugadores")->fetchColumn(),
        'pendiente_pago' => $pdo->query("SELECT COUNT(*) FROM padres WHERE metodo_pago = 'coordinador'")->fetchColumn()
    ];

    // Estadísticas por grupo
    $stats_grupo = $pdo->query("
        SELECT grupo, COUNT(*) as total
        FROM jugadores
        GROUP BY grupo
        ORDER BY FIELD(grupo, 'Querubin', 'Prebenjamin', 'Benjamin', 'Alevin')
    ")->fetchAll(PDO::FETCH_KEY_PAIR);

    // Estadísticas por demarcación
    $stats_demarcacion = $pdo->query("
        SELECT demarcacion, COUNT(*) as total
        FROM jugadores
        GROUP BY demarcacion
    ")->fetchAll(PDO::FETCH_KEY_PAIR);

    // Estadísticas por sexo
    $stats_sexo = $pdo->query("
        SELECT sexo, COUNT(*) as total
        FROM jugadores
        GROUP BY sexo
    ")->fetchAll(PDO::FETCH_KEY_PAIR);

    // Estadísticas de pagos
    $stats_pagos = $pdo->query("
        SELECT metodo_pago, COUNT(*) as total
        FROM padres
        GROUP BY metodo_pago
    ")->fetchAll(PDO::FETCH_KEY_PAIR);

    // Obtener todas las inscripciones con sus jugadores y contar hermanos
    $stmt = $pdo->query("
        SELECT 
            p.id AS padre_id,
            p.nombre AS padre_nombre,
            p.dni,
            p.telefono,
            p.email,
            p.metodo_pago,
            p.fecha_registro,
            GROUP_CONCAT(
                CONCAT(
                    j.nombre_completo, 
                    ' (', j.grupo, ')',
                    CASE 
                        WHEN j.jugador_numero > 1 THEN ' - Hermano ' || j.jugador_numero
                        ELSE ''
                    END
                ) 
                ORDER BY j.jugador_numero
                SEPARATOR '<br>'
            ) AS jugadores,
            COUNT(j.id) AS total_hijos,
            SUM(CASE WHEN j.jugador_numero > 1 THEN 1 ELSE 0 END) AS num_hermanos,
            SUM(d.descuento) AS total_descuento,
            90 + SUM(CASE WHEN d.tiene_hermanos THEN (90 - d.descuento) ELSE 0 END) AS precio_final
        FROM padres p
        LEFT JOIN jugadores j ON p.id = j.padre_id
        LEFT JOIN descuentos d ON j.id = d.jugador_id
        GROUP BY p.id
        ORDER BY p.fecha_registro DESC
    ");
    
    $inscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcular el balance económico
    $totalIngresos = array_sum(array_column($inscripciones, 'precio_final'));
    $totalDescuentos = array_sum(array_column($inscripciones, 'total_descuento'));
    $balanceEconomico = $totalIngresos;

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Campus de Fútbol</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="js/funciones.js"></script>
    <style>
        .card {
            border-radius: 15px;
        }
        .card-header {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .card-body {
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4 animate__animated animate__fadeInDown">
                    <i class="fas fa-tachometer-alt"></i> 
                    Dashboard Campus de Fútbol
                </h1>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow-sm animate__animated animate__fadeInLeft">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-users"></i> Total Inscripciones
                        </h5>
                        <h2><?= $stats['total_inscripciones'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white shadow-sm animate__animated animate__fadeInLeft">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-futbol"></i> Total Jugadores
                        </h5>
                        <h2><?= $stats['total_jugadores'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white shadow-sm animate__animated animate__fadeInLeft">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-clock"></i> Pendiente de Pago
                        </h5>
                        <h2><?= $stats['pendiente_pago'] ?></h2>
                    </div>
                </div>
            </div>

            <!-- Nuevas estadísticas -->
            <div class="col-12 mt-4">
                <div class="row g-4">
                    <!-- Estadísticas por Grupo -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm animate__animated animate__fadeInUp">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-users-class"></i> Por Grupo</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach ($stats_grupo as $grupo => $total): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span><?= htmlspecialchars($grupo) ?></span>
                                    <span class="badge bg-info"><?= $total ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas por Demarcación -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm animate__animated animate__fadeInUp">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-running"></i> Por Demarcación</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="display-4"><?= $stats_demarcacion['jugador'] ?? 0 ?></div>
                                        <small class="text-muted">Jugadores</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="display-4"><?= $stats_demarcacion['portero'] ?? 0 ?></div>
                                        <small class="text-muted">Porteros</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas por Sexo -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm animate__animated animate__fadeInUp">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-venus-mars"></i> Por Sexo</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="display-4"><?= $stats_sexo['H'] ?? 0 ?></div>
                                        <small class="text-muted">Hombres</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="display-4"><?= $stats_sexo['M'] ?? 0 ?></div>
                                        <small class="text-muted">Mujeres</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas de Pagos -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm animate__animated animate__fadeInUp">
                            <div class="card-header bg-warning text-white">
                                <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Por Método de Pago</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="display-4"><?= $stats_pagos['transferencia'] ?? 0 ?></div>
                                        <small class="text-muted">Transferencias</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="display-4"><?= $stats_pagos['coordinador'] ?? 0 ?></div>
                                        <small class="text-muted">Coordinador</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Económico -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-success text-white shadow-sm animate__animated animate__fadeInUp">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-balance-scale"></i> Balance Económico
                        </h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0">Total Ingresos: <?= $totalIngresos ?>€</p>
                                <p class="mb-0">Total Descuentos: <?= $totalDescuentos ?>€</p>
                            </div>
                            <div>
                                <h3 class="mb-0">Balance: <?= $balanceEconomico ?>€</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y Exportación -->
        <div class="card shadow-sm mb-4 animate__animated animate__fadeInUp">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="filtroGrupo" class="form-label">Filtrar por Categoría:</label>
                            <select id="filtroGrupo" class="form-select">
                                <option value="">Todas las categorías</option>
                                <option value="Querubin">Querubín</option>
                                <option value="Prebenjamin">Prebenjamín</option>
                                <option value="Benjamin">Benjamín</option>
                                <option value="Alevin">Alevín</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Inscripciones -->
        <div class="card shadow-sm animate__animated animate__fadeInUp">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tablaInscripciones" class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Padre/Tutor</th>
                                <th>DNI</th>
                                <th>Contacto</th>
                                <th>Jugadores/Hermanos</th>
                                <th>Total Hijos</th>
                                <th>Descuento</th>
                                <th>Precio Final</th>
                                <th>Pago</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscripciones as $i): ?>
                            <tr>
                                <td><?= $i['padre_id'] ?></td>
                                <td><?= htmlspecialchars($i['padre_nombre']) ?></td>
                                <td><?= htmlspecialchars($i['dni']) ?></td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($i['email']) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($i['email']) ?>
                                    </a>
                                    <br>
                                    <small><?= htmlspecialchars($i['telefono']) ?></small>
                                </td>
                                <td>
                                    <div class="jugadores-list">
                                        <?= $i['jugadores'] ?>
                                        <?php if ($i['num_hermanos'] > 0): ?>
                                            <span class="badge bg-info ms-2">
                                                <?= $i['num_hermanos'] ?> hermano(s)
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?= $i['total_hijos'] ?> hijos
                                    </span>
                                </td>
                                <td>
                                    <?php if ($i['total_descuento'] > 0): ?>
                                        <span class="badge bg-success">
                                            <?= $i['total_descuento'] ?>€
                                        </span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= $i['precio_final'] ?>€</td>
                                <td>
                                    <span class="badge bg-<?= $i['metodo_pago'] === 'transferencia' ? 'success' : 'warning' ?>">
                                        <?= $i['metodo_pago'] === 'transferencia' ? 'Transferencia' : 'Coordinador' ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($i['fecha_registro'])) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="inscripcion.php?id=<?= $i['padre_id'] ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger" 
                                           onclick="confirmarEliminacion(<?= $i['padre_id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm enviar-whatsapp" 
                                                data-telefono="<?= htmlspecialchars($i['telefono']) ?>"
                                                data-nombre="<?= htmlspecialchars($i['padre_nombre']) ?>"
                                                data-hijo="<?= htmlspecialchars($i['jugadores']) ?>">
                                            <i class="fab fa-whatsapp"></i> WhatsApp
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="whatsappModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enviar WhatsApp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="whatsappForm" action="send_wa.php" method="post">
                        <input type="hidden" name="telefono" id="whatsapp_telefono">
                        <div class="mb-3">
                            <label class="form-label">Mensaje</label>
                            <textarea class="form-control" name="mensaje" id="whatsapp_mensaje" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Enviar WhatsApp</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Crear una instancia del modal
        const whatsappModal = new bootstrap.Modal(document.getElementById('whatsappModal'));

        // Manejo del botón de WhatsApp
        $('.enviar-whatsapp').on('click', function() {
            const telefono = $(this).data('telefono');
            const nombre = $(this).data('nombre');
            const hijo = $(this).data('hijo');
            const metodoPago = $(this).closest('tr').find('td:eq(5) .badge').text().trim();
            
            // Construir mensaje predeterminado con emojis
            const mensaje = `¡Hola ${nombre}! 👋\n\n` +
                          `⚽ *CONFIRMACIÓN DE INSCRIPCIÓN - CAMPUS DE FÚTBOL* ⚽\n\n` +
                          `Te confirmamos la inscripción de:\n` +
                          `👤 ${hijo}\n\n` +
                          `🏃 en el Campus de Fútbol Racing Playa San Juan\n\n` +
                          `📝 *Información importante*:\n` +
                          `• Horario: 9:00 - 14:00\n` +
                          `• Lugar: Campo Municipal Racing Playa San Juan\n` +
                          `• Traer: Ropa deportiva, botella de agua y protección solar\n\n` +
                          `🎽 *Kit del campus*:\n` +
                          `• 2 camisetas de entrenamiento\n` +
                          `• 1 pantalón corto\n` +
                          `• 1 par de medias\n\n` +
                          `💳 *Forma de pago seleccionada*:\n` +
                          `${metodoPago}\n\n` +
                          `📞 *Contacto*:\n` +
                          `Para cualquier duda o consulta:\n` +
                          `• WhatsApp: 666777888\n` +
                          `• Email: campus@racingplayasanjuan.com\n\n` +
                          `¡Nos vemos pronto! ⚽🔥`;
            
            $('#whatsapp_telefono').val(telefono);
            $('#whatsapp_mensaje').val(mensaje);
            
            whatsappModal.show();
        });

        // Manejo del formulario de WhatsApp
        $('#whatsappForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const btnSubmit = form.find('button[type="submit"]');
            
            btnSubmit.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm"></span> Enviando...');
            
            $.ajax({
                type: 'POST',
                url: 'send_wa.php',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta del servidor:', response); // Debug
                    if (response.status === 'success') {
                        alert('✅ Mensaje enviado correctamente');
                        whatsappModal.hide();
                    } else {
                        if (response.message.includes('Recipient phone number not in allowed list')) {
                            alert('❌ Error: El número de teléfono del destinatario no está en la lista permitida.');
                        } else {
                            alert('❌ Error: ' + (response.message || 'Error desconocido'));
                        }
                        console.error('Error detallado:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', {xhr, status, error}); // Debug
                    alert('❌ Error en la petición: ' + error);
                },
                complete: function() {
                    btnSubmit.prop('disabled', false)
                            .html('Enviar WhatsApp');
                }
            });
        });

        // Filtrado de tabla
        $('#filtroGrupo').on('change', function() {
            const categoria = $(this).val();
            if (categoria) {
                $('tbody tr').hide().filter(function() {
                    return $(this).find('td:eq(4)').text().includes(categoria);
                }).show();
            } else {
                $('tbody tr').show();
            }
        });

        // Inicializar DataTables con exportación
        $('#tablaInscripciones').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            }
        });
    });
    </script>
    <style>
    .jugadores-list {
        max-width: 300px;
    }

    .jugadores-list .badge {
        font-size: 0.8rem;
        vertical-align: top;
    }
    </style>
</body>
</html>
