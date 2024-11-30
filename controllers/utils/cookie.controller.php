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

// Selección de liga
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $lligaSeleccionada = $_SESSION['lliga'];
} elseif (isset($_GET['lliga'])) {
    $lligaSeleccionada = $_GET['lliga'];
    setcookie('lliga', $lligaSeleccionada, time() + (86400 * 30), "/");
} elseif (isset($_COOKIE['lliga'])) {
    $lligaSeleccionada = $_COOKIE['lliga'];
} else {
    $lligaSeleccionada = 'LaLiga';
}

// Selección de liga
if (isset($_GET['orderby'])) {
    $partitsOrderBy = $_GET['orderby'];
    setcookie('partitsOrderBy', $partitsOrderBy, time() + (86400 * 30), "/");
} elseif (isset($_COOKIE['parttitsOrderBy'])) {
    $lligaSeleccionada = $_COOKIE['partitsOrderBy'];
} else {
    $lligaSeleccionada = 'date_desc';
}
?>