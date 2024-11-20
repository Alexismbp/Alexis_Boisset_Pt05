<?php
// FILE: controllers/main.controller.php

require_once __DIR__ . '/../models/utils/porra.model.php';

// Definir número de partidos por página
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
} else {
    if (isset($_GET['lliga'])) {
        $lligaSeleccionada = $_GET['lliga'];
        setcookie('lliga', $lligaSeleccionada, time() + (86400 * 30), "/");
    } elseif (isset($_COOKIE['lliga'])) {
        $lligaSeleccionada = $_COOKIE['lliga'];
    } else {
        $lligaSeleccionada = 'LaLiga'; // Valor por defecto
    }
}

// Definir número partits per pàgina
if (isset($_GET['partitsPerPage'])) {
    // Si s'ha passat el valor per GET l'agafem i aprofitem per crear la cookie
    $partitsPerPage = (int)$_GET['partitsPerPage'];
    setcookie('partitsPerPage', $partitsPerPage, time() + (86400 * 30), "/"); // Cookie vàlida per 30 díes
} elseif (isset($_COOKIE['partitsPerPage'])) {
    // Si no hi ha GET agafem valor de cookie (si existeix)
    $partitsPerPage = (int)$_COOKIE['partitsPerPage'];
} else {
    $partitsPerPage = 5; // Per defecte
}

// Selecció de lliga
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    // Si l'usuari està loguejat agafem la lliga del seu equip favorit
    $lligaSeleccionada = $_SESSION['lliga'];
} else {
    // Si no està loguejat agafem el valor seleccionat al <select> per GET
    if (isset($_GET['lliga'])) {
        $lligaSeleccionada = $_GET['lliga'];
        setcookie('lliga', $lligaSeleccionada, time() + (86400 * 30), "/"); // Cookie vàlida per 30 díes
    } elseif (isset($_COOKIE['lliga'])) {
        // Si no hi ha GET agafem valor de cookie (si existeix)
        $lligaSeleccionada = $_COOKIE['lliga'];
    } else {
        $lligaSeleccionada = 'LaLiga'; // Per defecte
    }
}

// Determinar la página actual
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $partitsPerPage;

// Obtenir partits de la lliga seleccionada
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $equipFavorit = $_SESSION['equip'];
    $partits = getPartits($lligaSeleccionada, $partitsPerPage, $offset, $equipFavorit);
} else {
    $partits = getPartits($lligaSeleccionada, $partitsPerPage, $offset);
}

// Calcular total de partits per la paginació
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $totalPartits = getTotalPartits($lligaSeleccionada, $equipFavorit);
} else {
    $totalPartits = getTotalPartits($lligaSeleccionada);
}

$totalPages = ceil($totalPartits / $partitsPerPage);

// Incluir la vista principal
include __DIR__ . '/../views/main/index.view.php';
?>