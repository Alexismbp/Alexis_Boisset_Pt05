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
            
            $avatarName = uniqid() . '_' . $avatar['name'];
            $uploadPath = __DIR__ . '/../../uploads/avatars/' . $avatarName;
            
            if (!move_uploaded_file($avatar['tmp_name'], $uploadPath)) {
                throw new Exception('Error al subir la imagen');
            }
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

// Obtener datos actuales del usuario
$userData = getUserData($email, $conn);
$equips = getAllTeams($conn);

// Incluir la vista
include __DIR__ . '/../../views/auth/profile/profile.view.php';