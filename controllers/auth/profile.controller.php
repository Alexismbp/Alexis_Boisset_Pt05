<?php
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/user/user.model.php';
require_once BASE_PATH . 'controllers/utils/SessionHelper.php';
require_once BASE_PATH . 'controllers/utils/validation.controller.php';

SessionHelper::checkLogin();

$conn = Database::getInstance();
$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $username = Validation::sanitizeInput($_POST['username']);
        $equip = Validation::sanitizeInput($_POST['equip']);

        // Validar campos
        $usernameError = Validation::validateUsername($username);
        $equipError = Validation::validateTeam($equip);

        if ($usernameError) throw new Exception($usernameError);
        if ($equipError) throw new Exception($equipError);

        // Manejo de la imagen de avatar
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatar = $_FILES['avatar'];
            if (!Validation::validateImage($avatar)) {
                throw new Exception('La imagen no cumple con los requisitos');
            }

            // Crear directorio de uploads si no existe
            $uploadDir = BASE_PATH . 'uploads/avatars';
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    throw new Exception('No se pudo crear el directorio de uploads');
                }
            }

            // Asegurarse que el directorio tiene permisos correctos
            chmod($uploadDir, 0755);

            $avatarName = uniqid("pfp", true) . $avatar['name'];
            $uploadPath = $uploadDir . '/' . $avatarName;

            if (!move_uploaded_file($avatar['tmp_name'], $uploadPath)) {
                throw new Exception('Error al subir la imagen. Verifica los permisos del directorio');
            }

            // Si hay un avatar anterior, eliminarlo
            if (isset($_SESSION['avatar']) && $_SESSION['avatar'] !== null) {
                $oldAvatar = $uploadDir . '/' . $_SESSION['avatar'];
                if (file_exists($oldAvatar)) {
                    unlink($oldAvatar);
                }
            }

            $_SESSION['avatar'] = $avatarName;
        } else {
            $avatarName = null;
        }

        if (updateUserProfile($email, $username, $equip, $avatarName, $conn)) {
            $_SESSION['username'] = $username;
            $_SESSION['equip'] = $equip;
            $_SESSION['success'] = 'Perfil actualitzat correctament';
        } else {
            throw new Exception('Error al actualitzar el perfil');
        }
    } catch (Exception $e) {
        $_SESSION['failure'] = $e->getMessage();
    }

    header('Location: ' . BASE_URL . 'profile');
    exit();
}

// Incluir la vista
include __DIR__ . '/../../views/auth/profile/profile.view.php';
