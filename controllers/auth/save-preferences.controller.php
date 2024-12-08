<?php
/**
 * Controlador para guardar las preferencias del usuario
 * 
 * Este script maneja el proceso de actualización de las preferencias de liga y equipo
 * del usuario en la base de datos.
 */

require_once __DIR__ . '/../../models/database/database.model.php';
require_once __DIR__ . '/../../models/user/user.model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $lliga = htmlspecialchars($_POST['lliga']) ?? '';
    $equip = htmlspecialchars($_POST['equip']) ?? '';
    
    // Validar que los campos no estén vacíos
    if (empty($lliga) || empty($equip)) {
        $_SESSION['failure'] = "Tots els camps són obligatoris";
        header('Location: ' . BASE_URL . 'preferences');
        exit;
    }

    try {
        // Actualizar preferencias en la base de datos
        $conn = Database::getInstance();
        if (updateUserPreferences($_SESSION['email'], $equip, $conn)) {
            // Actualizar sesión y redirigir al usuario
            unset($_SESSION['needs_preferences']);
            $_SESSION['lliga'] = $lliga;
            $_SESSION['equip'] = $equip;
            $_SESSION['success'] = "Preferències guardades correctament";
            header('Location: ' . BASE_URL);
        } else {
            throw new Exception("Error al guardar les preferències");
        }
    } catch (Exception $e) {
        // Manejar errores
        $_SESSION['failure'] = $e->getMessage();
        header('Location: ' . BASE_URL . 'preferences');
    }
    exit;
}