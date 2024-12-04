<?php
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../../controllers/utils/validation.controller.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['loggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL);
    exit;
}

try {
    $newPassword = Validation::sanitizeInput($_POST['new_password']);
    $confirmPassword = Validation::sanitizeInput($_POST['confirm_password']);

    $errors = Validation::validatePassword($newPassword, $confirmPassword);
    if ($errors) {
        throw new Exception($errors);
    }

    $conn = Database::getInstance();

    // Si es un usuario OAuth, no necesitamos verificar la contraseña actual
    if (isset($_SESSION['oauth_user'])) {
        if (updatePassword($_SESSION['email'], password_hash($newPassword, PASSWORD_DEFAULT), $conn)) {
            if (addPasswordToOAuthUser($_SESSION['email'], $conn)) {
            $_SESSION['success'] = "Contrasenya afegida correctament";
            unset($_SESSION['oauth_user']); // Ya no es solo un usuario OAuth
            header('Location: ' . BASE_URL);
            exit;
            }
        }
    } else {
        $currentPassword = Validation::sanitizeInput($_POST['current_password']);
        $hash = verifyCurrentPassword($_SESSION['email'], $currentPassword, $conn);

        if (password_verify($currentPassword, $hash)) {
            if (updatePassword($_SESSION['email'], password_hash($newPassword, PASSWORD_DEFAULT), $conn)) {
                $_SESSION['success'] = "Contrasenya actualitzada correctament";
                header('Location: ' . BASE_URL);
                exit;
            }
        } else {
            throw new Exception("La contrasenya actual no és correcta");
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    SessionHelper::setSessionData([
        'error' => $_SESSION['error']
    ]);
    header('Location: ' . BASE_URL . 'changepassword');
    exit;
}
