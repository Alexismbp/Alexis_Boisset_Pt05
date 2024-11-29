<?php
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../../controllers/utils/validation.controller.php';

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['loggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL);
    exit;
}

try {
    $currentPassword = Validation::sanitizeInput($_POST['current_password']);
    $newPassword = Validation::sanitizeInput($_POST['new_password']);
    $confirmPassword = Validation::sanitizeInput($_POST['confirm_password']);
    
    $errors = Validation::validatePassword($newPassword, $confirmPassword);
    if ($errors) {
        throw new Exception($errors);
    }
    
    $conn = Database::getInstance();
    if (verifyCurrentPassword($_SESSION['email'], $currentPassword, $conn)) {
        if (updatePassword($_SESSION['email'], password_hash($newPassword, PASSWORD_DEFAULT), $conn)) {
            $_SESSION['success'] = "Contrasenya actualitzada correctament";
            header('Location: ' . BASE_URL);
            exit;
        }
    } else {
        throw new Exception("La contrasenya actual no és correcta");
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ' . BASE_URL . 'changepassword');
    exit;
}