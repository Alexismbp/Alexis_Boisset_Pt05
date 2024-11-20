<?php
class FormController {
    public static function clearFormFields($fields) {
        foreach ($fields as $field) {
            if (isset($_SESSION[$field])) {
                unset($_SESSION[$field]);
            }
        }
    }

    public static function getClearFormUrl($returnUrl = null) {
        $currentUrl = $returnUrl ?? $_SERVER['PHP_SELF'];
        return $currentUrl . (strpos($currentUrl, '?') !== false ? '&' : '?') . 'netejar=true';
    }
}