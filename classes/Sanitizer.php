<?php
class Sanitizer {
    public static function sanitizeString($str) {
        if ($str === null) return '';
        $str = trim($str);
        // Reemplazar FILTER_SANITIZE_STRING con htmlspecialchars
        return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function sanitizeName($name) {
        $name = self::sanitizeString($name);
        // Sanitizar nombre permitiendo caracteres españoles
        $name = preg_replace('/[^a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/', '', $name);
        return trim(preg_replace('/\s+/', ' ', $name));
    }

    public static function sanitizeDNI($dni) {
        $dni = strtoupper(trim($dni));
        return preg_replace('/[^0-9A-Z]/', '', $dni);
    }

    public static function sanitizeEmail($email) {
        $email = trim($email);
        // Usar validación de email más estricta
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    public static function sanitizePhone($phone) {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    public static function sanitizeDate($date) {
        try {
            $dateObj = new DateTime($date);
            return $dateObj->format('Y-m-d');
        } catch (Exception $e) {
            return '';
        }
    }

    public static function validateInput($input, $type) {
        $input = trim($input);
        
        switch ($type) {
            case 'nombre':
                return preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,100}$/', $input);
            case 'dni':
                return preg_match('/^[0-9]{8}[A-Z]$/', $input);
            case 'email':
                return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
            case 'telefono':
                return preg_match('/^[6789][0-9]{8}$/', $input);
            case 'fecha':
                if (!$input) return false;
                try {
                    $date = new DateTime($input);
                    return $date && $date->format('Y-m-d') === $input;
                } catch (Exception $e) {
                    return false;
                }
            default:
                return false;
        }
    }
}

// Suprimir advertencias de depreciación para evitar el error de headers
error_reporting(E_ALL & ~E_DEPRECATED);
