<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus de Fútbol | Racing Playa San Juan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light text-dark">
    <!-- Navbar fixed top -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Campus de Fútbol</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inscripcion.php">Inscripción</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="info.php">Info</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header Banner -->
    <header id="inicio" class="header-banner text-center py-5 parallax" style="margin-top: 80px;">
        <div class="container mx-auto">
            <img src="images/cabecera.png" alt="Cabecera" class="img-fluid mb-4" style="max-width: 300px;">
            <h1 class="display-4 text-white">¡Vive la Pasión del Fútbol!</h1>
            <p class="lead text-white">Inscríbete en nuestro Campus de Semana Santa y desarrolla tus habilidades.</p>
            <a href="inscripcion.php" class="btn btn-success btn-lg">
                <i class="fas fa-paper-plane me-2"></i> Inscríbete Ahora
            </a>
        </div>
    </header>

    <!-- Sección de Destacados -->
    <section id="destacados" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">¿Por qué elegir nuestro Campus?</h2>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-futbol fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Entrenamiento de Calidad</h5>
                            <p class="card-text">Entrenadores cualificados y programas personalizados.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-map-marker-alt fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Instalaciones Cabo Huerta</h5>
                            <p class="card-text">Campo de fútbol de césped sintético.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Ambiente Inolvidable</h5>
                            <p class="card-text">Actividades recreativas y convivencia con otros jóvenes.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-shield fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Entrenamiento para Porteros</h5>
                            <p class="card-text">Programa especializado para desarrollar las habilidades de los porteros.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Información General -->
    <section id="info" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Información General</h2>
            <div class="row">
                <div class="col-md-4">
                    <ul class="list-unstyled">
                        <li class="info-item">
                            <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                            <h3>Fecha</h3>
                            <p>22, 23, 24 y 25 de Abril</p>
                        </li>
                        <li class="info-item">
                            <i class="fas fa-child fa-2x text-warning mb-2"></i>
                            <h3>Edades</h3>
                            <p>De 5 a 11 años</p>
                        </li>
                        <li class="info-item">
                            <i class="fas fa-user-shield fa-2x text-danger mb-2"></i>
                            <h3>Entrenadores titulados</h3>
                            <p>Contamos con entrenadores titulados y experimentados.</p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-unstyled">
                        <li class="info-item">
                            <i class="fas fa-map-marker-alt fa-2x text-success mb-2"></i>
                            <h3>Lugar</h3>
                            <p>Instalaciones Racing Playa San Juan</p>
                        </li>
                        <li class="info-item">
                            <i class="fas fa-euro-sign fa-2x text-info mb-2"></i>
                            <h3>Precio</h3>
                            <p>95€ (Equipación y seguro)</p>
                        </li>
                        <li class="info-item">
                            <i class="fas fa-layer-group fa-2x text-primary mb-2"></i>
                            <h3>Grupos por nivel</h3>
                            <p>Participantes en grupos por nivel.</p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-unstyled">
                        <li class="info-item">
                            <i class="fas fa-tshirt fa-2x text-info mb-2"></i>
                            <h3>Camiseta</h3>
                            <p>Cada participante recibe una camiseta.</p>
                        </li>
                        <li class="info-item">
                            <i class="fas fa-utensils fa-2x text-warning mb-2"></i>
                            <h3>Almuerzo</h3>
                            <p>Almuerzo saludable con fruta.</p>
                        </li>
                        <li class="info-item">
                            <i class="fas fa-certificate fa-2x text-secondary mb-2"></i>
                            <h3>Diploma</h3>
                            <p>Diploma de tecnificación al finalizar.</p>
                        </li>
                        <li class="info-item">
                            <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                            <h3>Seguros</h3>
                            <p>Seguros de accidente y RC.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-3">
        <p>&copy; 2025 Campus de Fútbol Racing Playa San Juan</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="funciones.js?v=<?= time() ?>"></script>
</body>
</html>
