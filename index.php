<!-- Alexis Boisset -->
<?php
require_once "models/database/database.model.php"; // Fitxer per obtenir connexió a la base de dades
require_once "controller/config.controller.php"; // Fitxer per detectar innactivitat
require_once "models/utils/porra.model.php"; // Fitxer per obtenir partits

$conn = Database::connect(); // Connexió a la base de dades

session_start();

// Definir número partits per pàgina
if (isset($_GET['partitsPerPage'])) {
    // Si s'ha passat el valor per GET l'agafem i aprofitem per crear la cookie
    $partitsPerPage = (int)$_GET['partitsPerPage'];
    setcookie('partitsPerPage', $partitsPerPage, time() + (86400 * 30), "/"); // Cookie vàlida per 30 díes
} elseif (isset($_COOKIE['partitsPerPage'])) {
    // Si no hi ha GET agafem valor de cookie (si existeix)
    $partitsPerPage = (int)$_COOKIE['partitsPerPage'];
} else {
    $partitsPerPage = 5; // Valor per defecte
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
        $lligaSeleccionada = 'LaLiga'; // Valor per defecte
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

include "views/index.view.php";
?>