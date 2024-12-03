
<?php
require_once __DIR__ . '/../../models/env.php';
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/user/user.model.php';
require_once BASE_PATH . '/controllers/session/session.controller.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['userid'] != 1) {
    header("Location: " . BASE_URL);
    exit();
}

$conn = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    
    if ($userId && deleteUser($userId, $conn)) {
        $_SESSION['success'] = "Usuario eliminado correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar el usuario.";
    }
    
    header("Location: " . BASE_URL . "manage-users");
    exit();
}

// Incluir la vista
include BASE_PATH . '/views/admin/manage-users.view.php';
?>