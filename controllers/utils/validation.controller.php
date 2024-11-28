<?php

class Validation {
    public static function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    public static function validateUsername($username) {
        if (empty($username)) {
            return "El nom d'usuari no pot estar buit";
        }
        return null;
    }

    public static function validatePassword($password, $confirmPassword) {
        if (empty($password)) {
            return "És obligatori una contrasenya";
        }

        $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
        if (preg_match($passwordPattern, $password) === 0) {
            return "La contrasenya ha de tenir mínim: 8 caràcters, una majúscula, una minúscula i un dígit";
        }

        if ($password !== $confirmPassword) {
            return "Les contrasenyes no coincideixen";
        }

        return null;
    }

    public static function validateEmail($email) {
        if (empty($email)) {
            return "És obligatori un correu electrònic";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Format de correu electrònic no vàlid";
        }
        return null;
    }

    public static function validateTeam($team) {
        if (empty($team)) {
            return "És obligatori un equip favorit";
        }
        return null;
    }

    // Nuevas validaciones para login
    public static function validateLogin($email, $password) {
        $errors = [];
        
        $emailError = self::validateEmail($email);
        if ($emailError) $errors[] = $emailError;

        if (empty($password)) {
            $errors[] = "La contrasenya no pot estar buida";
        }

        return $errors;
    }

    // Validación para reset password
    public static function validateResetPassword($password, $confirmPassword) {
        $errors = [];
        
        $passwordError = self::validatePassword($password, $confirmPassword);
        if ($passwordError) $errors[] = $passwordError;

        return $errors;
    }
}