<?php
// Alexis Boisset

require_once __DIR__ . '/../../models/env.php';
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/user/user.model.php';
require_once BASE_PATH . 'controllers/session/session.controller.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// SessionHelper::checkLogin() no utilizado en este controlador porque necesito verificar que es el usuario admin;
if (!isset($_SESSION['loggedin']) || $_SESSION['userid'] != 1) {
    $_SESSION['failure'] = "No tens permisos per accedir a aquesta pàgina (sigue intentandolo Xavi)."; // Comenario de ánimo para que sigas corrigiendo 🫡
    header("Location: " . BASE_URL);
    exit();
}

$conn = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        
        if (!$userId) {
            throw new Exception("ID de usuario no válido");
        }
        
        if (deleteUser($userId, $conn)) {
            $_SESSION['success'] = "Usuario eliminado correctamente.";
        } else {
            throw new Exception("Error al eliminar el usuario");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
    
    header("Location: " . BASE_URL . "manage-users");
    exit;
}

// Incluir la vista
include BASE_PATH . 'views/admin/manage-users.view.php';
?>