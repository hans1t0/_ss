<?php
/**
 * Funciones compartidas para los templates
 */

if (!function_exists('generarCampo')) {
    function generarCampo($nombre, $campo, $valor = '', $numero = '') {
        $id = $nombre . $numero;
        // Recuperar valor anterior si existe
        $valorAnterior = isset($_POST[$id]) ? htmlspecialchars($_POST[$id]) : $valor;
        
        $html = '<div class="form-group">';
        
        if (isset($campo['label'])) {
            $html .= "<label class='form-label' for='{$id}'>{$campo['label']}</label>";
        }
        
        $validationRules = isset($campo['validacion']) ? ' data-validacion="' . $campo['validacion'] . '"' : '';
        
        switch ($campo['tipo']) {
            case 'select':
                $html .= "<select name='{$id}' id='{$id}' class='{$campo['class']}'{$validationRules}" . 
                        ($campo['required'] ? ' required' : '') . ">";
                $html .= "<option value=''>Seleccione una opción</option>";
                foreach ($campo['opciones'] as $value => $label) {
                    $selected = $valorAnterior == $value ? ' selected' : '';
                    $html .= "<option value='{$value}'{$selected}>{$label}</option>";
                }
                $html .= "</select>";
                $html .= "<div class='invalid-feedback'></div>";
                break;
                
            default:
                $html .= "<input type='{$campo['tipo']}' id='{$id}' name='{$id}' " .
                        "class='{$campo['class']}'{$validationRules}" .
                        ($campo['required'] ? ' required' : '') .
                        (isset($campo['placeholder']) ? " placeholder='{$campo['placeholder']}'" : '') .
                        " value='{$valorAnterior}'>";
                $html .= "<div class='invalid-feedback'></div>";
        }
        
        $html .= '</div>';
        return $html;
    }
}

// Otras funciones comunes aquí...
