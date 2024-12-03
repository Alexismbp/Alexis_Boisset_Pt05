<?php
require_once __DIR__ . '/../../models/database/database.model.php';
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../utils/SessionHelper.php';

class AuthMiddleware {
    public static function handleRememberToken() {
        // Si el usuario ya está autenticado, no hace falta verificar el token
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            return;
        }

        // Verificar si existe la cookie remember_token
        if (isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            
            $conn = Database::getInstance();
            
            // Intentar obtener el usuario por el token
            $userData = getUserByRememberToken($token, $conn);
            
            if ($userData) {
                
                // Guardar solo el email en la sesión
                SessionHelper::setSessionData(['remembered_email' => $userData['correu_electronic']]);
                

                // Renovar el token
                $newToken = bin2hex(random_bytes(32));
                $expiry = time() + (30 * 24 * 60 * 60); // 30 días
                
                storeRememberToken($userData['id'], $newToken, $expiry, $conn);
                
                setcookie(
                    'remember_token',
                    $newToken,
                    [
                        'expires' => $expiry,
                        'path' => '/',
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]
                );
                
            } else {
                
                // Si el token no es válido, eliminarlo
                setcookie('remember_token', '', time() - 3600, '/');
            }
        }
    }
}