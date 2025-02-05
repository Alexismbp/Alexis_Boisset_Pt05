<?php
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/user/user.model.php';
require_once BASE_PATH . 'controllers/utils/SessionHelper.php';
require_once BASE_PATH . 'controllers/utils/validation.controller.php';
require_once BASE_PATH . 'models/api/apiKey.model.php';

SessionHelper::checkLogin();

$conn = Database::getInstance();
$email = $_SESSION['email'];

// Afegir gestió per generar API Key
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_api_key'])) {
    if (!isset($_SESSION['userid'])) {
        $_SESSION['failure'] = 'Usuario no encontrado';
    } else {
        $newApiKey = generateApiKey($conn, $_SESSION['userid']);
        $_SESSION['api_key'] = $newApiKey;
        $_SESSION['success'] = 'API Key generada correctament';
    }
    header('Location: ' . BASE_URL . 'profile');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recibir datos
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

            // Directorio de subida
            $uploadDir = BASE_PATH . 'uploads/avatars';

            // Crear directorio SI NO existe
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    throw new Exception('No se pudo crear el directorio de uploads');
                }
            }

            // Generar nombre seguro para el archivo (SIN espacios ni caracteres especiales)
            $fileExtension = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));
            $fileExtension = preg_replace('/[^a-zA-Z0-9]/', '', $fileExtension);
            $randomString = bin2hex(random_bytes(5)); // 10 caracteres hexadecimales
            $avatarName = 'avatar_' . $randomString . '.' . $fileExtension;
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
            $avatarName = isset($_SESSION['avatar']) ? $_SESSION['avatar'] : null;
        }

        if (updateUserProfile($email, $username, $equip, $avatarName, $conn)) {
            $_SESSION['username'] = $username;
            $_SESSION['equip'] = $equip;
            $_SESSION['avatar'] = $avatarName ?? $_SESSION['avatar'];
            $_SESSION['lliga'] = getLeagueName($equip, $conn); // Añadir esta línea
            setcookie('lliga', $_SESSION['lliga'], time() + (86400 * 30), "/");
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
