<?php
// Alexis Boisset

require_once __DIR__ . '/../../models/database/database.model.php';
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../utils/validation.controller.php';
require_once __DIR__ . '/../utils/recaptcha.controller.php';
require_once __DIR__ . '/../utils/session.helper.php';

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
            unset($_SESSION['email']);
            $_SESSION['LAST_ACTIVITY'] = time();
            $_SESSION['loggedin'] = true;
            $_SESSION['userid'] = $userData['id'];
            $_SESSION['username'] = $userData['nom_usuari'];
            $_SESSION['equip'] = $userData['equip_favorit'];
            $_SESSION['lliga'] = getLeagueName($userData['equip_favorit'], $conn);

            header("Location: " . BASE_URL);
            exit();
        } else {
            SessionHelper::incrementLoginAttempts();
            $_SESSION['failure'] = "Credencials incorrectes";
        }
    }
} catch (Exception $e) {
    $_SESSION['failure'] = "Error: " . $e->getMessage();
} finally {
    header("Location: " . BASE_URL . "login");
    exit();
}
