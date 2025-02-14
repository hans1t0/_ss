<?php
/**
 * Template para campos del jugador
 */

// Solo mantener los campos del jugador
$campos_jugador = [
    'hijo_nombre_completo' => [
        'tipo' => 'text',
        'label' => 'Nombre y Apellidos del jugador',
        'required' => true,
        'placeholder' => 'Nombre completo del jugador',
        'class' => 'form-control'
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
        'tipo' => 'radio',
        'label' => 'Sexo',
        'required' => true,
        'class' => 'form-check-input',
        'opciones' => [
            'H' => 'Hombre',
            'M' => 'Mujer'
        ]
    ],
    'demarcacion' => [
        'tipo' => 'radio',
        'label' => 'Demarcación',
        'required' => true,
        'class' => 'form-check-input',
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

// Función para generar HTML de un campo
function generarCampo($nombre, $campo, $valor = '', $numero = '') {
    $html = '<div class="mb-3">';
    $id = $nombre . $numero;
    
    // Añadir label si existe
    if (isset($campo['label'])) {
        $html .= "<label class='form-label' for='{$id}'>{$campo['label']}</label>";
    }
    
    switch ($campo['tipo']) {
        case 'select':
            $html .= "<select name='{$id}' id='{$id}' class='{$campo['class']}'" . 
                    ($campo['required'] ? ' required' : '') . ">";
            $html .= "<option value='' selected disabled>Seleccione una opción</option>";
            foreach ($campo['opciones'] as $value => $label) {
                $selected = $valor == $value ? ' selected' : '';
                $html .= "<option value='{$value}'{$selected}>{$label}</option>";
            }
            $html .= "</select>";
            break;
            
        case 'radio':
            $html .= "<div class='form-control py-2'>";
            foreach ($campo['opciones'] as $value => $label) {
                $checked = $valor == $value ? ' checked' : '';
                $html .= "
                    <div class='form-check form-check-inline'>
                        <input type='radio' id='{$id}_{$value}' name='{$id}' value='{$value}' 
                               class='{$campo['class']}'{$checked}" . 
                               ($campo['required'] ? ' required' : '') . ">
                        <label class='form-check-label' for='{$id}_{$value}'>{$label}</label>
                    </div>";
            }
            $html .= "</div>";
            break;
            
        case 'textarea':
            $html .= "<textarea name='{$id}' id='{$id}' class='{$campo['class']}'" .
                    ($campo['required'] ? ' required' : '') .
                    (isset($campo['placeholder']) ? " placeholder='{$campo['placeholder']}'" : '') .
                    (isset($campo['rows']) ? " rows='{$campo['rows']}'" : '') .
                    ">{$valor}</textarea>";
            break;
            
        default:
            $html .= "<input type='{$campo['tipo']}' id='{$id}' name='{$id}' " .
                    "class='{$campo['class']}'" .
                    ($campo['required'] ? ' required' : '') .
                    (isset($campo['placeholder']) ? " placeholder='{$campo['placeholder']}'" : '') .
                    (isset($campo['pattern']) ? " pattern='{$campo['pattern']}'" : '') .
                    " value='{$valor}'>";
    }
    
    $html .= '</div>';
    return $html;
}
