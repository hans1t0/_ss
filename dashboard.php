<?php
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

    // Obtener estadísticas generales
    $stats = [
        'total_jugadores' => $pdo->query("SELECT COUNT(*) FROM jugadores")->fetchColumn(),
        'total_familias' => $pdo->query("SELECT COUNT(DISTINCT padre_id) FROM jugadores")->fetchColumn(),
        'promedio_hermanos' => $pdo->query("SELECT AVG(hermanos) FROM (SELECT padre_id, COUNT(*) as hermanos FROM jugadores GROUP BY padre_id) as t")->fetchColumn(),
    ];

    // Obtener distribución por grupo de edad
    $grupos = $pdo->query("
        SELECT grupo, COUNT(*) as total 
        FROM jugadores 
        GROUP BY grupo
        ORDER BY FIELD(grupo, 'Querubin', 'Prebenjamin', 'Benjamin', 'Alevin')
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener distribución por sexo
    $sexos = $pdo->query("
        SELECT sexo, COUNT(*) as total 
        FROM jugadores 
        GROUP BY sexo
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener distribución por demarcación
    $demarcaciones = $pdo->query("
        SELECT demarcacion, COUNT(*) as total 
        FROM jugadores 
        GROUP BY demarcacion
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener distribución por modalidad
    $modalidades = $pdo->query("
        SELECT modalidad, COUNT(*) as total 
        FROM jugadores 
        GROUP BY modalidad
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Añadir nueva consulta para métodos de pago
    $metodos_pago = $pdo->query("
        SELECT metodo_pago, COUNT(*) as total 
        FROM padres 
        GROUP BY metodo_pago
    ")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Campus de Fútbol</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-chart-line me-2"></i>Dashboard Campus
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-light ms-2" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Resumen en Tarjetas -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <div class="display-4 text-primary mb-2">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="card-title">Total Jugadores</h5>
                        <p class="display-6"><?= $stats['total_jugadores'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <div class="display-4 text-primary mb-2">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="card-title">Total Familias</h5>
                        <p class="display-6"><?= $stats['total_familias'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <div class="display-4 text-primary mb-2">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="card-title">Promedio Hermanos</h5>
                        <p class="display-6"><?= number_format($stats['promedio_hermanos'], 1) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Filtros</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <select class="form-select" id="filtroGrupo">
                            <option value="">Todos los grupos</option>
                            <?php foreach ($grupos as $grupo): ?>
                                <option value="<?= htmlspecialchars($grupo['grupo']) ?>">
                                    <?= htmlspecialchars($grupo['grupo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos con Tabs -->
        <div class="card mb-4">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="grupos-tab" data-bs-toggle="tab" data-bs-target="#gruposTab" type="button" role="tab">
                            <i class="fas fa-users"></i> Grupos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="sexos-tab" data-bs-toggle="tab" data-bs-target="#sexosTab" type="button" role="tab">
                            <i class="fas fa-venus-mars"></i> Sexo
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pagos-tab" data-bs-toggle="tab" data-bs-target="#pagosTab" type="button" role="tab">
                            <i class="fas fa-money-bill"></i> Formas de Pago
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content pt-4" id="myTabContent">
                    <div class="tab-pane fade show active" id="gruposTab" role="tabpanel">
                        <div style="height: 300px" class="chart-container">
                            <canvas id="gruposChart"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="sexosTab" role="tabpanel">
                        <div style="height: 300px" class="chart-container">
                            <canvas id="sexosChart"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pagosTab" role="tabpanel">
                        <div style="height: 300px" class="chart-container">
                            <canvas id="pagosChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Jugadores Mejorada -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title d-flex justify-content-between align-items-center">
                    <span>Listado de Jugadores</span>
                    <button class="btn btn-sm btn-outline-primary" id="exportCsv">
                        <i class="fas fa-download"></i> Exportar CSV
                    </button>
                </h5>
                <div class="table-responsive">
                    <table class="table table-striped" id="jugadoresTable">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Grupo</th>
                                <th>Sexo</th>
                                <th>Demarcación</th>
                                <th>Modalidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $jugadores = $pdo->query("SELECT * FROM jugadores ORDER BY grupo, nombre_completo")->fetchAll();
                            foreach ($jugadores as $jugador): ?>
                            <tr>
                                <td><?= htmlspecialchars($jugador['nombre_completo']) ?></td>
                                <td><?= htmlspecialchars($jugador['grupo']) ?></td>
                                <td><?= $jugador['sexo'] === 'H' ? 'Hombre' : 'Mujer' ?></td>
                                <td><?= ucfirst($jugador['demarcacion']) ?></td>
                                <td><?= $jugador['modalidad'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>
$(document).ready(function() {
    // Variables para almacenar las instancias de los gráficos
    let charts = {};
    
    // Función para destruir un gráfico existente
    function destroyChart(chartId) {
        if (charts[chartId]) {
            charts[chartId].destroy();
            charts[chartId] = null;
        }
    }

    // Función para crear o actualizar un gráfico
    function createChart(elementId, data) {
        // Primero destruimos el gráfico existente si lo hay
        destroyChart(elementId);
        
        const ctx = document.getElementById(elementId).getContext('2d');
        charts[elementId] = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.map(item => item.label),
                datasets: [{
                    data: data.map(item => item.total),
                    backgroundColor: [
                        '#3498db', '#2ecc71', '#e74c3c', '#f1c40f',
                        '#9b59b6', '#1abc9c', '#e67e22', '#34495e'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 15
                        }
                    }
                }
            }
        });
    }

    // Datos para los gráficos
    const gruposData = <?= json_encode(array_map(function($item) {
        return ['label' => $item['grupo'], 'total' => (int)$item['total']];
    }, $grupos)) ?>;

    const sexosData = <?= json_encode(array_map(function($item) {
        return ['label' => $item['sexo'] === 'H' ? 'Hombre' : 'Mujer', 'total' => (int)$item['total']];
    }, $sexos)) ?>;

    const pagosData = <?= json_encode(array_map(function($item) {
        return [
            'label' => $item['metodo_pago'] === 'transferencia' ? 'Transferencia Bancaria' : 'Pago al Coordinador',
            'total' => (int)$item['total']
        ];
    }, $metodos_pago)) ?>;

    // Crear gráfico inicial
    createChart('gruposChart', gruposData);

    // Manejar el cambio de tabs
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).data('bs-target');
        switch(target) {
            case '#gruposTab':
                createChart('gruposChart', gruposData);
                break;
            case '#sexosTab':
                createChart('sexosChart', sexosData);
                break;
            case '#pagosTab':
                createChart('pagosChart', pagosData);
                break;
        }
    });

    // Inicializar DataTables
    const table = $('#jugadoresTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
        },
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: ['csv', 'print']
    });

    // Filtros
    $('#filtroGrupo').on('change', function() {
        table.column(1).search(this.value).draw();
    });

    // Exportar CSV
    $('#exportCsv').click(function() {
        const csvContent = "data:text/csv;charset=utf-8,";
        // ... código para generar CSV ...
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "jugadores.csv");
        document.body.appendChild(link);
        link.click();
    });
});
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    .card {
        break-inside: avoid;
    }
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.nav-tabs .nav-link {
    color: #2c3e50;
}

.nav-tabs .nav-link.active {
    color: #3498db;
    border-bottom: 2px solid #3498db;
}

.table th {
    background-color: #f8f9fa;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #3498db !important;
    color: white !important;
    border: none;
}

.btn-outline-primary {
    border-color: #3498db;
    color: #3498db;
}

.btn-outline-primary:hover {
    background-color: #3498db;
    color: white;
}

/* Estilos para los gráficos */
.tab-content {
    background-color: #fff;
    border-radius: 0 0 8px 8px;
    padding: 20px;
}

.tab-pane {
    transition: all 0.3s ease;
}

.chart-container {
    position: relative;
    margin: auto;
}

/* Mejorar visualización de tabs */
.nav-tabs {
    border-bottom: 2px solid #dee2e6;
}

.nav-tabs .nav-link {
    margin-bottom: -2px;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 0.5rem 1rem;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    border-bottom: 2px solid #3498db;
}

.tab-content {
    padding-top: 2rem;
}

.chart-container h6 {
    color: #2c3e50;
    font-weight: 500;
}
</style>
</body>
</html>
