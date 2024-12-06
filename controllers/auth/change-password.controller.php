<?php
// Alexis Boisset
// Importación de los modelos y controladores necesarios
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../../controllers/utils/validation.controller.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado y si la petición es POST
if (!isset($_SESSION['loggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL);
    exit;
}

try {
    // Obtener y sanitizar los datos del formulario
    $newPassword = Validation::sanitizeInput($_POST['new_password']);
    $confirmPassword = Validation::sanitizeInput($_POST['confirm_password']);

    // Validar que las contraseñas cumplan con los requisitos
    $errors = Validation::validatePassword($newPassword, $confirmPassword);
    if ($errors) {
        throw new Exception($errors);
    }

    // Obtener la conexión a la base de datos
    $conn = Database::getInstance();

    // Procesar el cambio de contraseña para usuarios OAuth
    if (isset($_SESSION['oauth_user'])) {
        if (updatePassword($_SESSION['email'], password_hash($newPassword, PASSWORD_DEFAULT), $conn)) {
            if (addPasswordToOAuthUser($_SESSION['email'], $conn)) {
                $_SESSION['success'] = "Contrasenya afegida correctament";
                unset($_SESSION['oauth_user']); // Eliminar marca de usuario OAuth
                header('Location: ' . BASE_URL);
                exit;
            }
        }
    } else {
        // Procesar el cambio de contraseña para usuarios normales
        $currentPassword = Validation::sanitizeInput($_POST['current_password']);
        $hash = verifyCurrentPassword($_SESSION['email'], $currentPassword, $conn);

        // Verificar que la contraseña actual sea correcta
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
    // Manejar errores y redirigir con mensaje de error

    $_SESSION['error'] = $e->getMessage();
    SessionHelper::setSessionData([
        'error' => $_SESSION['error']
    ]);
    header('Location: ' . BASE_URL . 'changepassword');
    exit;
}
