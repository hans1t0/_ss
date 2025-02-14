<?php
/**
 * Template para campos del padre/tutor
 */

$campos_padre = [
    'nombre' => [
        'tipo' => 'text',
        'label' => 'Nombre del Padre/Tutor',
        'required' => true,
        'placeholder' => 'Nombre completo',
        'class' => 'form-control'
    ],
    'dni' => [
        'tipo' => 'text',
        'label' => 'DNI/NIE',
        'required' => true,
        'placeholder' => '12345678X',
        'class' => 'form-control'
    ],
    'telefono' => [
        'tipo' => 'tel',
        'label' => 'Teléfono',
        'required' => true,
        'placeholder' => '666777888',
        'pattern' => '[0-9]{9}',
        'title' => 'Número de teléfono (9 dígitos)',
        'class' => 'form-control'
    ],
    'email' => [
        'tipo' => 'email',
        'label' => 'Correo Electrónico',
        'required' => true,
        'placeholder' => 'ejemplo@email.com',
        'class' => 'form-control'
    ],
    'metodo_pago' => [
        'tipo' => 'select',
        'label' => 'Forma de Pago',
        'required' => true,
        'class' => 'form-control',
        'name' => 'metodo_pago', // Añadido para mantener el nombre exacto
        'opciones' => [
            'T' => 'Transferencia Bancaria',
            'C' => 'Pago al Coordinador'
        ]
    ],
    'cuenta_bancaria' => [
        'tipo' => 'text',
        'label' => 'IBAN para Cargo',
        'required' => false,
        'placeholder' => 'ES + 22 dígitos',
        'pattern' => 'ES[0-9]{2}[0-9]{20}',
        'title' => 'IBAN español: ES seguido de 22 números',
        'class' => 'form-control',
        'help_text' => 'Formato: ES seguido de 22 números (ejemplo: ES6621000418401234567891)',
        'container_id' => 'cuenta_bancaria_container',
        'condicion_mostrar' => 'metodo_pago === "T"'
    ]
];

// Si la función generarCampo no está definida, la definimos
if (!function_exists('generarCampo')) {
    require_once __DIR__ . '/template_usuario.php';
}

// Función específica para generar campos del padre
function generarCamposPadre($campos_padre) {
    $html = '';
    foreach ($campos_padre as $nombre => $campo) {
        $html .= '<div class="col-md-6">';
        if (isset($campo['container_id'])) {
            $html .= "<div id='{$campo['container_id']}'>";
        }
        
        // Usar el nombre exacto si está especificado
        $nombre_campo = isset($campo['name']) ? $campo['name'] : 'padre_' . $nombre;
        $html .= generarCampo($nombre_campo, $campo);
        
        if (isset($campo['help_text'])) {
            $html .= "<div class='form-text'>{$campo['help_text']}</div>";
        }
        
        if (isset($campo['container_id'])) {
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    return $html;
}
