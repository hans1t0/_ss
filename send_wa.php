<?php
header('Content-Type: application/json');

// Activar todos los errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para logging
function logWhatsApp($message, $data = null) {
    $log = date('Y-m-d H:i:s') . " - " . $message;
    if ($data) {
        $log .= "\n" . print_r($data, true);
    }
    file_put_contents(__DIR__ . '/whatsapp.log', $log . "\n\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telefono = $_POST['telefono'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';
    
    // Log datos recibidos
    logWhatsApp("Datos recibidos:", ['telefono' => $telefono, 'mensaje' => $mensaje]);
    
    // Limpieza y validación del número de teléfono
    $telefono = preg_replace('/[^0-9]/', '', $telefono);
    
    // Validación básica
    if (empty($telefono) || empty($mensaje)) {
        logWhatsApp("Error de validación: Datos vacíos");
        echo json_encode(['status' => 'error', 'message' => 'Teléfono y mensaje son requeridos']);
        exit;
    }

    if (strlen($telefono) !== 9) {
        logWhatsApp("Error de validación: Longitud del teléfono incorrecta");
        echo json_encode(['status' => 'error', 'message' => 'El número de teléfono debe tener 9 dígitos']);
        exit;
    }
    
    // Configuración de la API de WhatsApp
    $token = 'EAAIOyA5wqXoBO1k6qq47AWGZA5HScNkR03iKUpJXjBJj8UjAqaerjaOZC8XB389PosfGGUBVHypLBOZB6NZC20QZA0ddrQ029VGPyOSkqx0G62ZBvOoKcWfalFbJZA0h8oZB26BBnZCZBD02H52lgdDvNxm4Fn1TurVbX2tZASGxhjvrj7iLDioIIzMDADGiPloyMM07GgfEAfNaVtIRsQh';
    $telefono_id = '547808498413811';
    $version = 'v21.0';  // Versión actualizada de la API
    
    // Formato del número de teléfono
    $telefono_completo = '34' . $telefono;
    
    // URL de la API
    $url = "https://graph.facebook.com/{$version}/{$telefono_id}/messages";
    
    // Datos del mensaje con formato mejorado
    $data = [
        'messaging_product' => 'whatsapp',
        'recipient_type' => 'individual',
        'to' => $telefono_completo,
        'type' => 'text',
        'text' => [
            'preview_url' => false,
            'body' => $mensaje
        ]
    ];
    
    // Log request
    logWhatsApp("Request a WhatsApp API:", [
        'url' => $url,
        'data' => $data,
        'headers' => [
            'Authorization' => 'Bearer ' . substr($token, 0, 20) . '...',
            'Content-Type' => 'application/json'
        ]
    ]);
    
    // Configuración de cURL con verificación SSL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);  // Verificación SSL activada
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);     // Verificación del host
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    
    // Buffer para el debug de cURL
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verbose);
    
    // Ejecutar la petición
    $response = curl_exec($ch);
    $err = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Log verbose output
    rewind($verbose);
    $verboseLog = stream_get_contents($verbose);
    logWhatsApp("cURL verbose log:", $verboseLog);
    
    if ($err) {
        logWhatsApp("Error de cURL:", $err);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error de cURL: ' . $err
        ]);
    } else {
        $result = json_decode($response, true);
        logWhatsApp("Respuesta de WhatsApp API:", [
            'httpCode' => $httpCode,
            'response' => $result
        ]);
        
        if ($httpCode == 200) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Mensaje enviado correctamente',
                'response' => $result
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error en la API: ' . ($result['error']['message'] ?? 'Error desconocido'),
                'response' => $result
            ]);
        }
    }
    
    curl_close($ch);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
exit;
