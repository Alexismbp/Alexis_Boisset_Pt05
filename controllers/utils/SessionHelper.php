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
}
?>