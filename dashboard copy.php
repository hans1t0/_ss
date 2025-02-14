<?php
session_start();

// Configuración de la base de datos
$config = [
    'host' => 'localhost',
    'dbname' => 'ss_campus_db',
    'user' => 'root',
    'password' => 'hans'
];

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['user'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Estadísticas generales
    $stats = [
        'total_inscripciones' => $pdo->query("SELECT COUNT(*) FROM padres")->fetchColumn(),
        'total_jugadores' => $pdo->query("SELECT COUNT(*) FROM jugadores")->fetchColumn(),
        'pendiente_pago' => $pdo->query("SELECT COUNT(*) FROM padres WHERE metodo_pago = 'C'")->fetchColumn()
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

    // Obtener todas las inscripciones con sus jugadores
    $stmt = $pdo->query("
        SELECT 
            p.id, 
            p.nombre AS padre_nombre,
            p.dni,
            p.telefono,
            p.email,
            p.metodo_pago,
            p.fecha_registro,
            GROUP_CONCAT(
                CONCAT(
                    j.hijo_nombre_completo, 
                    ' (', j.grupo, ')'
                ) 
                SEPARATOR ', '
            ) AS jugadores
        FROM padres p
        LEFT JOIN jugadores j ON p.id = j.padre_id
        GROUP BY p.id
        ORDER BY p.fecha_registro DESC
    ");
    
    $inscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">
                    <i class="fas fa-tachometer-alt"></i> 
                    Dashboard Campus de Fútbol
                </h1>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-users"></i> Total Inscripciones
                        </h5>
                        <h2><?= $stats['total_inscripciones'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-futbol"></i> Total Jugadores
                        </h5>
                        <h2><?= $stats['total_jugadores'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
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
                        <div class="card h-100">
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
                        <div class="card h-100">
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
                        <div class="card h-100">
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
                        <div class="card h-100">
                            <div class="card-header bg-warning text-white">
                                <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Por Método de Pago</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="display-4"><?= $stats_pagos['T'] ?? 0 ?></div>
                                        <small class="text-muted">Transferencias</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="display-4"><?= $stats_pagos['C'] ?? 0 ?></div>
                                        <small class="text-muted">Coordinador</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Inscripciones -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Padre/Tutor</th>
                                <th>DNI</th>
                                <th>Contacto</th>
                                <th>Jugadores</th>
                                <th>Pago</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscripciones as $i): ?>
                            <tr>
                                <td><?= $i['id'] ?></td>
                                <td><?= htmlspecialchars($i['padre_nombre']) ?></td>
                                <td><?= htmlspecialchars($i['dni']) ?></td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($i['email']) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($i['email']) ?>
                                    </a>
                                    <br>
                                    <small><?= htmlspecialchars($i['telefono']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($i['jugadores']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $i['metodo_pago'] === 'T' ? 'success' : 'warning' ?>">
                                        <?= $i['metodo_pago'] === 'T' ? 'Transferencia' : 'Coordinador' ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($i['fecha_registro'])) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="ver_inscripcion.php?id=<?= $i['id'] ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger" 
                                           onclick="confirmarEliminacion(<?= $i['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </a>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    function confirmarEliminacion(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta inscripción?')) {
            window.location.href = `eliminar_inscripcion.php?id=${id}`;
        }
    }
    </script>

    <style>
    .card {
        margin-bottom: 1rem;
        border: none;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .table th {
        background-color: #f8f9fa;
    }
    .badge {
        padding: 0.5em 1em;
    }
    .display-4 {
        font-size: 2.5rem;
        font-weight: 300;
        line-height: 1.2;
    }
    .card-header h5 {
        margin-bottom: 0;
    }
    .badge {
        font-size: 1rem;
    }
    </style>
</body>
</html>
