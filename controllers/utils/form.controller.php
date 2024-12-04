<?php
class FormController {
    public static function clearFormFields($fields) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        foreach ($fields as $field) {
            if (isset($_SESSION[$field])) {
                unset($_SESSION[$field]);
            }
        }
        
        // Limpiar campos específicos del formulario de registro
        if (isset($_SESSION['username'])) unset($_SESSION['username']);
        if (isset($_SESSION['email'])) unset($_SESSION['email']);
        if (isset($_SESSION['lliga'])) unset($_SESSION['lliga']);
        if (isset($_SESSION['equip'])) unset($_SESSION['equip']);
        
        // Limpiar cualquier mensaje de error o éxito
        if (isset($_SESSION['error'])) unset($_SESSION['error']);
        if (isset($_SESSION['success'])) unset($_SESSION['success']);
    }

    public static function getClearFormUrl($returnUrl = null) {
        $currentUrl = $returnUrl ?? $_SERVER['PHP_SELF'];
        return $currentUrl . (strpos($currentUrl, '?') !== false ? '&' : '?') . 'netejar=true';
    }
}