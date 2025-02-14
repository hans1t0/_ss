<?php
require_once __DIR__ . '/functions.php';

/**
 * Template para campos del jugador
 */

$campos_jugador = [
    'hijo_nombre_completo' => [
        'tipo' => 'text',
        'label' => 'Nombre y Apellidos del jugador',
        'required' => true,
        'placeholder' => 'Nombre completo del jugador',
        'class' => 'form-control',
        'validacion' => 'nombre'
    ],
    'hijo_fecha_nacimiento' => [
        'tipo' => 'date',
        'label' => 'Fecha de Nacimiento',
        'required' => true,
        'class' => 'form-control'
    ],
    'grupo' => [
        'tipo' => 'select',
        'label' => 'Grupo de Edad',
        'required' => true,
        'class' => 'form-control',
        'opciones' => [
            'Querubin' => 'Querubines (5 años)',
            'Prebenjamin' => 'Prebenjamin (6-7 años)',
            'Benjamin' => 'Benjamines (8-9 años)',
            'Alevin' => 'Alevines (10-11 años)'
        ]
    ],
    'sexo' => [
        'tipo' => 'select',
        'label' => 'Sexo',
        'required' => true,
        'class' => 'form-control',
        'opciones' => [
            'H' => 'Hombre',
            'M' => 'Mujer'
        ]
    ],
    'demarcacion' => [
        'tipo' => 'select',
        'label' => 'Demarcación',
        'required' => true,
        'class' => 'form-control',
        'opciones' => [
            'jugador' => 'Jugador campo',
            'portero' => 'Portero'
        ]
    ],
    'modalidad' => [
        'tipo' => 'select',
        'label' => 'Modalidad',
        'required' => true,
        'class' => 'form-control',
        'opciones' => [
            'RPSJ' => 'Jugador de RPSJ',
            'NO_RPSJ' => 'No jugador de RPSJ'
        ]
    ],
    'lesiones' => [
        'tipo' => 'textarea',
        'label' => 'Lesiones o Alergias',
        'required' => false,
        'placeholder' => 'Opcional',
        'class' => 'form-control',
        'rows' => 1
    ]
];

// Eliminar la función generarCampo() de este archivo ya que está en functions.php
