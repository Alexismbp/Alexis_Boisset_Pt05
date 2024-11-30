
<?php
require_once __DIR__ . '/../../models/database/database.model.php';
require_once __DIR__ . '/../../models/user/user.model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lliga = $_POST['lliga'] ?? '';
    $equip = $_POST['equip'] ?? '';
    
    if (empty($lliga) || empty($equip)) {
        $_SESSION['failure'] = "Tots els camps són obligatoris";
        header('Location: ' . BASE_URL . 'preferences');
        exit;
    }

    try {
        $conn = Database::getInstance();
        if (updateUserPreferences($_SESSION['email'], $equip, $conn)) {
            unset($_SESSION['needs_preferences']);
            $_SESSION['lliga'] = $lliga;
            $_SESSION['equip'] = $equip;
            $_SESSION['success'] = "Preferències guardades correctament";
            header('Location: ' . BASE_URL);
        } else {
            throw new Exception("Error al guardar les preferències");
        }
    } catch (Exception $e) {
        $_SESSION['failure'] = $e->getMessage();
        header('Location: ' . BASE_URL . 'preferences');
    }
    exit;
}