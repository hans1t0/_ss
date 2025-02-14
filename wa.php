<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head></head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar WhatsApp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body class="bg-light"></body>
    <div class="container py-5">
        <div class="card">
            <div class="card-body"></div>
                <h3 class="card-title mb-4">Enviar Mensaje WhatsApp</h3>
                <form action="send_wa.php" method="post"></form>
                    <div class="mb-3">
                        <label class="form-label">Número de Teléfono</label>
                        <input type="tel" class="form-control" name="telefono" required 
                               pattern="[0-9]{9}" placeholder="Ejemplo: 666777888">
                    </div>
                    <div class="mb-3"></div>
                        <label class="form-label">Mensaje</label>
                        <textarea class="form-control" name="mensaje" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
