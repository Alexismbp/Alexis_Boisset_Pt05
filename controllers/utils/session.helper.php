
<?php
class SessionHelper {
    public static function saveFormData($data) {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    public static function getFormValue($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : '';
    }

    public static function incrementLoginAttempts() {
        $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? 
            $_SESSION['login_attempts'] + 1 : 1;
    }

    public static function resetLoginAttempts() {
        $_SESSION['login_attempts'] = 0;
    }

    public static function needsCaptcha() {
        return isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3;
    }
}