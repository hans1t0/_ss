<?php
require_once __DIR__ . '/functions.php';

/**
 * Template para campos del padre/tutor
 */

$campos_padre = [
    'padre_nombre' => [
        'tipo' => 'text',
        'label' => 'Nombre y Apellidos del Padre/Tutor',
        'required' => true,
        'placeholder' => 'Nombre completo del padre/tutor',
        'class' => 'form-control',
        'validacion' => 'nombre'
    ],
    'padre_dni' => [
        'tipo' => 'text',
        'label' => 'DNI',
        'required' => true,
        'placeholder' => '12345678A',
        'class' => 'form-control',
        'validacion' => 'dni'
    ],
    'padre_telefono' => [
        'tipo' => 'text',
        'label' => 'Teléfono',
        'required' => true,
        'placeholder' => 'Teléfono de contacto',
        'class' => 'form-control'
    ],
    'padre_email' => [
        'tipo' => 'email',
        'label' => 'Email',
        'required' => true,
        'placeholder' => 'Email de contacto',
        'class' => 'form-control'
    ],
    'metodo_pago' => [
        'tipo' => 'select',
        'label' => 'Método de Pago',
        'required' => true,
        'opciones' => [
            'transferencia' => 'Transferencia Bancaria',
            'coordinador' => 'Pago al Coordinador'
        ],
        'value' => '',
        'placeholder' => 'Seleccione método de pago'
    ]
];

// Función para generar HTML de los campos del padre/tutor
function generarCamposPadre($campos) {
    $html = '';
    foreach ($campos as $nombre => $campo) {
        $html .= generarCampo($nombre, $campo);
    }
    return $html;
}

// Función para generar la información de pago
function generarInformacionPago() {
    return '<div class="info-pago">
        <div id="info_pago" class="alert alert-info">
            <div id="info_transferencia">
                <h5 class="alert-heading">Información Bancaria</h5>
                <p><strong>IBAN:</strong> ES12 3456 7890 1234 5678 9012</p>
                <p><strong>Beneficiario:</strong> Racing Playa San Juan</p>
                <p><strong>Concepto:</strong> Campus + Nombre del jugador</p>
            </div>
            <div id="info_coordinador" style="display:none">
                <h5 class="alert-heading">Pago al Coordinador</h5>
                <p>Podrá realizar el pago directamente al coordinador del campus.</p>
                <p><strong>Teléfono:</strong> 666777888</p>
            </div>
        </div>
    </div>';
}
