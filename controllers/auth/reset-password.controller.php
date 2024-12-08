<?php
/**
 * Controlador para restablecer la contraseña
 * Maneja la lógica de verificación y actualización de contraseñas
 */

require_once BASE_PATH . 'models/user/user.model.php';
require_once BASE_PATH . 'controllers/utils/validation.controller.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar datos del formulario
    $token = htmlspecialchars($_POST['token']);
    $newPassword = htmlspecialchars($_POST['new_password']);
    $confirmPassword = htmlspecialchars($_POST['confirm_password']);

    // Validar la nueva contraseña
    $errors = Validation::validateResetPassword($newPassword, $confirmPassword);
    if (!empty($errors)) {
        $_SESSION['failure'] = implode('<br>', $errors);
        SessionHelper::setSessionData([
            'failure' => $_SESSION['failure']
        ]);
        header('Location: ' . BASE_URL . 'resetpassword?token=' . $token);
        exit();
    }

    // Verificar token y obtener email asociado
    $conn = Database::getInstance();
    $email = verifyToken($token, $conn);

    if ($email) {
        // Actualizar contraseña en la base de datos
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        if (updatePassword($email, $hashedPassword, $conn)) {
            $_SESSION['success'] = 'Contrasenya restablerta correctament.';
            header('Location: ' . BASE_URL . 'login');
            exit();
        } else {
            $_SESSION['failure'] = 'No s\'ha pogut restablir la contrasenya.';
        }
    } else {
        $_SESSION['failure'] = 'Token invàlid o caducat.';
    }

    SessionHelper::setSessionData([
        'failure' => $_SESSION['failure']
    ]);

    header('Location: ' . BASE_URL . 'resetpassword?token=' . $token);
    exit();
}