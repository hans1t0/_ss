<?php
require_once __DIR__ . '/functions.php';

/**
 * Template para campos del jugador
 */
$campos_jugador = [
    'hijo_nombre_completo' => [
        'tipo' => 'text',
        'label' => 'Nombre Completo del Jugador',
        'required' => true,
        'placeholder' => 'Nombre completo',
        'class' => 'form-control',
        'validacion' => 'nombre'
    ],
    'hijo_fecha_nacimiento' => [
        'tipo' => 'date',
        'label' => 'Fecha de Nacimiento',
        'required' => true,
        'placeholder' => 'AAAA-MM-DD',
        'class' => 'form-control datepicker'
    ],
    'sexo' => [
        'tipo' => 'select',
        'label' => 'Género',
        'required' => true,
        'opciones' => [
            'H' => 'Hombre',
            'M' => 'Mujer'
        ],
        'class' => 'form-select'
    ],
    'grupo' => [
        'tipo' => 'select',
        'label' => 'Grupo',
        'required' => true,
        'opciones' => [
            'Querubin' => 'Querubines (5 años)',
            'Prebenjamin' => 'Prebenjamin (6-7 años)',
            'Benjamin' => 'Benjamines (8-9 años)',
            'Alevin' => 'Alevines (10-11 años)'
        ],
        'class' => 'form-select'
    ],
    'modalidad' => [
        'tipo' => 'select',
        'label' => 'Modalidad',
        'required' => true,
        'opciones' => [
            'RPSJ' => 'RPSJ',
            'NO_RPSJ' => 'NO_RPSJ'
        ],
        'class' => 'form-select'
    ],
    'demarcacion' => [
        'tipo' => 'select',
        'label' => 'Demarcación',
        'required' => true,
        'opciones' => [
            'jugador' => 'Jugador',
            'portero' => 'Portero'
        ],
        'class' => 'form-select'
    ],
    'lesiones' => [
        'tipo' => 'text',
        'label' => 'Lesiones',
        'required' => false,
        'placeholder' => 'Lesiones',
        'class' => 'form-control'
    ]
];
