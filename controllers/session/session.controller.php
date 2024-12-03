<?php
// Alexis Boisset
// Control de inactivitat (tret de StackOverflow)
// Obtener la URL base del servidor

if (!session_id()) {
    session_start();
}
// Al inicio del archivo, después del session_start()
if (session_status() === PHP_SESSION_ACTIVE) {
    
}
// 2400 segundos = 40 minutos
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 2400) && (isset($_SESSION['loggedin']) && $_SESSION['loggedin'])) {
    // Si han pasado más de 40 minutos
    session_unset();
    session_destroy();
    session_start(); // Vuelvo a abrir la session para tener feedback de sesion expirada
    $_SESSION['failure'] = "Sessió expirada";

    header("Location: " . BASE_URL . "login"); // Redirigir al login
    exit();
} elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $_SESSION['LAST_ACTIVITY'] = time(); // Actualizar el tiempo de última actividad
}
