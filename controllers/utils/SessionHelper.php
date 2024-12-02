<?php
class SessionHelper {
    public static function incrementLoginAttempts() {
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    }

    public static function resetLoginAttempts() {
        $_SESSION['login_attempts'] = 0;
    }

    public static function needsCaptcha() {
        return ($_SESSION['login_attempts'] ?? 0) >= 3;
    }

    public static function saveFormData($data) {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    public static function getFormValue($key) {
        return $_SESSION[$key] ?? '';
    }

    public static function setSessionData($data) {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * Verifica si el usuario está logueado, si no, redirige al login
     */
    public static function checkLogin(): void {
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            $_SESSION['failure'] = "Has d'iniciar sessió per accedir a aquesta pàgina";
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
    }
}
?>