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
            SessionHelper::setSessionData([
                'email' => $email,
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
