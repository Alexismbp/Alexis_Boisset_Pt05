<?php
// Alexis Boisset

require_once __DIR__ . '/../../models/database/database.model.php';
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../utils/validation.controller.php';
require_once __DIR__ . '/../utils/ReCaptchaController.php';
require_once __DIR__ . '/../utils/SessionHelper.php';

// DEBUGG
/* $_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['email'] = 'alexis@gmail.com';
$_POST['password'] = 'Admin123'; */

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = Validation::sanitizeInput($_POST['email']);
        $password = Validation::sanitizeInput($_POST['password']);
        
        if (SessionHelper::needsCaptcha()) {
            $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
            if (!ReCaptchaController::verifyResponse($recaptcha_response)) {
                SessionHelper::saveFormData(['email' => $email]);
                throw new Exception('Por favor, completa el captcha correctamente.');
            }
        }

        // Validar campos
        $errors = Validation::validateLogin($email, $password);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            throw new Exception();
        }

        $userData = getUserData($email, $conn);

        if ($userData && password_verify($password, $userData['contrasenya'])) {
            
            SessionHelper::resetLoginAttempts();
            
            // Handle remember me
            if (isset($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(32));
                $expiry = time() + (30 * 24 * 60 * 60); // 30 days
                
                // Store token in database
                storeRememberToken($userData['id'], $token, $expiry, $conn);
                
                // Set remember me cookie
                setcookie(
                    'remember_token',
                    $token,
                    [
                        'expires' => $expiry,
                        'path' => '/',
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]
                );
            } else {
                // Remove remember me cookie
                setcookie('remember_token', '', time() - 3600, '/');
            }

            SessionHelper::setSessionData([
                'email' => $email,
                'avatar' => $userData['avatar'] ?? 'default-avatar.webp',
                'LAST_ACTIVITY' => time(),
                'loggedin' => true,
                'userid' => $userData['id'],
                'username' => $userData['nom_usuari'],
                'equip' => $userData['equip_favorit'],
                'lliga' => getLeagueName($userData['equip_favorit'], $conn)
            ]);
            header("Location: " . BASE_URL);
            exit();
        } else {
            SessionHelper::incrementLoginAttempts();
            throw new Exception("Credencials incorrectes", 1);
        
        }
    }
} catch (Exception $e) {
    $_SESSION['failure'] = "Error: " . $e->getMessage();
    SessionHelper::saveFormData(['email' => $email]);
} finally {
    header("Location: " . BASE_URL . "login");
    exit();
}
