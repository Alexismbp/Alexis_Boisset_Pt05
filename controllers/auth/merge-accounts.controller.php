
<?php
require_once __DIR__ . '/../../models/database/database.model.php';
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../utils/SessionHelper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $email = $_POST['email'] ?? '';
    $provider = $_POST['provider'] ?? '';
    
    // Si se confirma la fusión de cuentas
    if ($action === 'merge') {
        try {
            $conn = Database::getInstance();
            if (mergeAccounts($email, $provider, $conn)) {
                $_SESSION['success'] = "Comptes fusionats correctament!";
                // Limpiar variables temporales
                unset($_SESSION['temp_email'], $_SESSION['temp_name'], $_SESSION['temp_provider']);
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['failure'] = "Error al fusionar els comptes: " . $e->getMessage();
        }
    }
    
    // Si se cancela o hay error, redirigir al login
    header('Location: ' . BASE_URL . 'login');
    exit;
}