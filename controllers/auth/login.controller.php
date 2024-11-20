<?php
// Alexis Boisset

require_once __DIR__ . '/../../models/database/database.model.php';
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../utils/validation.controller.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = Validation::sanitizeInput($_POST['email']);
        $password = Validation::sanitizeInput($_POST['password']);
        
        // Validar campos
        $errors = Validation::validateLogin($email, $password);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            throw new Exception();
        }

        $userData = getUserData($email, $conn);

        if ($userData && password_verify($password, $userData['contrasenya'])) {
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
            $_SESSION['failure'] = "Credencials incorrectes";
        }
    }
} catch (Exception $e) {
    $_SESSION['failure'] = "Error: " . $e->getMessage();
} finally {
    header("Location: " . BASE_URL . "login");
    exit();
}
