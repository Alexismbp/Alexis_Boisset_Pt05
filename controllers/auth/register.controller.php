<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once BASE_PATH . 'models/user/user.model.php';
require_once BASE_PATH . 'controllers/utils/validation.controller.php';
require_once BASE_PATH . 'controllers/utils/ReCaptchaController.php';
require_once BASE_PATH . 'controllers/utils/SessionHelper.php';

try {

    $missatgesError = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Guardar los datos del formulario primero
        $nomUsuari = Validation::sanitizeInput($_POST['username']);
        $email = Validation::sanitizeInput($_POST['email']);
        $equipFavorit = Validation::sanitizeInput($_POST['equip']);

        // Verificar captcha si es necesario
        if (SessionHelper::needsCaptcha()) {
            $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
            if (!ReCaptchaController::verifyResponse($recaptcha_response)) {
                $_SESSION['username'] = $nomUsuari;
                $_SESSION['email'] = $email;
                $_SESSION['equip'] = $equipFavorit;
                throw new Exception('Por favor, completa el captcha correctamente.');
            }
        }

        // Resto de validaciones
        $contrasenya = Validation::sanitizeInput($_POST['password']);
        $passwordConfirm = Validation::sanitizeInput($_POST['password_confirm']);

        // Validar campos
        $errors = array_filter([
            Validation::validateUsername($nomUsuari),
            Validation::validatePassword($contrasenya, $passwordConfirm),
            Validation::validateEmail($email),
            Validation::validateTeam($equipFavorit)
        ]);

        if (!empty($errors)) {
            $missatgesError = array_merge($missatgesError, $errors);
            throw new Exception();
        }

        // Encriptar contraseña
        $contrasenyaHashed = password_hash($contrasenya, PASSWORD_DEFAULT);

        // Registrar usuario
        if (registerUser($nomUsuari, $email, $contrasenyaHashed, $equipFavorit, $conn)) {
            // Iniciar sesión
            $userData = getUserData($email, $conn);
            SessionHelper::setSessionData([
                'email' => $email,
                'oauth_user' => $userData['is_oauth_user'],
                'avatar' => $userData['avatar'] ?? 'default-avatar.webp',
                'LAST_ACTIVITY' => time(),
                'loggedin' => true,
                'userid' => $userData['id'],
                'username' => $userData['nom_usuari'],
                'equip' => $userData['equip_favorit'],
                'lliga' => getLeagueName($userData['equip_favorit'], $conn),
                'success' => 'Usuari registrat correctament'
            ]);
            header("Location: " . BASE_URL);
            exit();
        } else {
            $missatgesError[] = "Aquest correu electrònic ja s'està utilitzant";
            throw new Exception();
        }
    }
} catch (Throwable $th) {
    $_SESSION['failure'] = empty($th->getMessage()) ? null : "Hi ha hagut un error: " . $th->getMessage();
    SessionHelper::setSessionData([
        'errors' => $missatgesError,
        'username' => $nomUsuari ?? '',
        'email' => $email ?? '',
        'lliga' => isset($equipFavorit) ? getLeagueName($equipFavorit, $conn) : '',
        'equip' => $equipFavorit ?? ''
    ]);
} finally {
    header("Location: " . BASE_URL . "register");
    exit();
}
