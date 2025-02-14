<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario de consentimiento
    $consentimiento_datos = isset($_POST['consentimiento_datos']) ? 1 : 0;
    $consentimiento_imagen = isset($_POST['consentimiento_imagen']) ? 1 : 0;

    // Guardar los consentimientos en la base de datos o realizar otras acciones necesarias
    // ...

    // Redirigir a una página de éxito o mostrar un mensaje de éxito
    header('Location: consentimiento_exito.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento de Tratamiento de Datos e Imágenes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h1 class="display-4 mb-4">Consentimiento de Tratamiento de Datos e Imágenes</h1>
                        <form action="consentimiento.php" method="post">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="consentimiento_datos" id="consentimiento_datos" required>
                                <label class="form-check-label" for="consentimiento_datos">
                                    <i class="fas fa-check-circle me-2"></i>Acepto el tratamiento de los datos proporcionados
                                </label>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="consentimiento_imagen" id="consentimiento_imagen" required>
                                <label class="form-check-label" for="consentimiento_imagen">
                                    <i class="fas fa-check-circle me-2"></i>Acepto el tratamiento de las imágenes proporcionadas
                                </label>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Consentimiento
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
