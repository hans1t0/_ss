<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus de Fútbol | Información General</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .header-banner {
            background-image: url('futbol-banner.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 60px 0;
        }
        .header-banner h1 {
            font-size: 3rem;
        }
        .icon {
            font-size: 1.5rem;
            color: #007bff;
            margin-right: 10px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Campus de Fútbol</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inscripcion.php">Inscripción</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="info.php">Info</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="header-banner text-center" style="margin-top: 80px;">
        <div class="container mx-auto">
            <h1 class="text-5xl font-bold">Campus de Fútbol</h1>
            <p class="text-xl mt-4">Información General</p>
        </div>
    </div>

    <div class="container mx-auto py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h2 class="text-2xl font-bold mb-4"><i class="fas fa-calendar-alt icon"></i>Fecha</h2>
                <p class="text-lg">22, 23, 24 y 25 de Abril</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h2 class="text-2xl font-bold mb-4"><i class="fas fa-child icon"></i>Edades</h2>
                <p class="text-lg">de 5 a 11 años</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h2 class="text-2xl font-bold mb-4"><i class="fas fa-map-marker-alt icon"></i>Lugar</h2>
                <p class="text-lg">Campo Municipal El Cabo</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h2 class="text-2xl font-bold mb-4"><i class="fas fa-clock icon"></i>Horarios</h2>
                <div class="text-left">
                    <p class="mb-2"><strong>9:00 a 9:15:</strong> Activación</p>
                    <p class="mb-2"><strong>9:20 a 10:20:</strong> Sesión de trabajo técnico individual / Específico Porteros</p>
                    <p class="mb-2"><strong>10:25 a 11:25:</strong> Sesión de trabajo táctico colectivo</p>
                    <p class="mb-2"><strong>11:30 a 12:00:</strong> Almuerzo</p>
                    <p class="mb-2"><strong>12:05 a 13:30:</strong> Aplicación conceptos en situación real de juego</p>
                    <p class="mb-2"><strong>13:30 a 13:45:</strong> Charla final y vuelta a la calma</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg text-center col-span-1 md:col-span-2">
                <h2 class="text-2xl font-bold mb-4"><i class="fas fa-euro-sign icon"></i>Tarifa de Inscripción</h2>
                <p class="text-lg"><strong>Cuota Campus:</strong> 95€</p>
                <p class="text-lg"><strong>Descuento Familiar:</strong></p>
                <ul class="list-disc list-inside text-left">
                    <li>5€ segundo hij@</li>
                    <li>10€ tercer hij@</li>
                </ul>
            </div>
        </div>

        <div class="text-center mt-10">
            <a href="inscripcion.php" class="bg-blue-500 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition duration-300">
                <i class="fas fa-paper-plane mr-2"></i>Inscribirse Ahora
            </a>
        </div>
    </div>
</body>
</html>
