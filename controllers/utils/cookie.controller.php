<?php
// Definir número de partidos por página
$partidosPerPageOptions = [5, 10, 15, 20];
if (isset($_GET['partitsPerPage'])) {
    $partitsPerPage = (int)$_GET['partitsPerPage'];
    setcookie('partitsPerPage', $partitsPerPage, time() + (86400 * 30), "/");
} elseif (isset($_COOKIE['partitsPerPage'])) {
    $partitsPerPage = (int)$_COOKIE['partitsPerPage'];
} else {
    $partitsPerPage = 5; // Valor por defecto
}

// Asegurar valores por defecto
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $lligaSeleccionada = $_SESSION['lliga'] ?? 'LaLiga';
} elseif (isset($_GET['lliga'])) {
    $lligaSeleccionada = $_GET['lliga'];
    setcookie('lliga', $lligaSeleccionada, time() + (86400 * 30), "/");
} elseif (isset($_COOKIE['lliga'])) {
    $lligaSeleccionada = $_COOKIE['lliga'];
} else {
    $lligaSeleccionada = 'LaLiga';
    setcookie('lliga', $lligaSeleccionada, time() + (86400 * 30), "/");
}

// Selección de orden
if (isset($_GET['orderBy'])) {
    $orderBy = $_GET['orderBy'];
    setcookie('orderBy', $orderBy, time() + (86400 * 30), "/");
} elseif (isset($_COOKIE['orderBy'])) {
    $orderBy = $_COOKIE['orderBy'];
} else {
    $orderBy = 'date_desc';
}
?>