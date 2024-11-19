<?php
// Alexis Boisset
// Control de inactivitat (tret de StackOverflow)
// Obtener la URL base del servidor

session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 2400) && ($_SESSION['loggedin'])) {
    // Si han pasado más de 40 minutos
    session_unset();
    session_destroy();
    session_start(); // Vuelvo a abrir la session para tener feedback de sesion expirada
    $_SESSION['failure'] = "Sessió expirada";

    header("Location: " . BASE_URL . "/views/auth/login/login.view.php"); // Redirigir al login
    exit();
} elseif ($_SESSION['loggedin'] == true) {
    $_SESSION['LAST_ACTIVITY'] = time(); // Actualizar el tiempo de última actividad
}
