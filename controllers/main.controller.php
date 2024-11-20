<?php
require_once __DIR__ . '/../models/utils/porra.model.php';

// Definir ligas disponibles
$lligues = ['LaLiga', 'Premier League', 'Ligue 1'];

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

// Pasar todas las variables necesarias a la vista
$viewData = [
    'lligues' => $lligues,
    'lligaSeleccionada' => $lligaSeleccionada,
    'partitsPerPage' => $partitsPerPage,
    'partidosPerPageOptions' => $partidosPerPageOptions,
    'partits' => $partits,
    'currentPage' => $page,
    'totalPages' => $totalPages
];

// Incluir la vista principal
extract($viewData);
include __DIR__ . '/../views/main/index.view.php';
?>