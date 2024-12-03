<?php
require_once BASE_PATH . 'models/utils/porra.model.php';

// Definir ligas disponibles
$lligues = ['LaLiga', 'Premier League', 'Ligue 1'];

// Gestio de cookies
require_once BASE_PATH . 'controllers/utils/cookie.controller.php';

// Determinar la página actual
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $partitsPerPage;

// Mapeo de valores de ordenación a columnas de la base de datos
$orderMappings = [
    'date_asc' => ['column' => 'p.data', 'direction' => 'ASC'],
    'date_desc' => ['column' => 'p.data', 'direction' => 'DESC'],
    'name_asc' => ['column' => 'e_local.nom', 'direction' => 'ASC'],
    'name_desc' => ['column' => 'e_local.nom', 'direction' => 'DESC']
];

// Obtener orden seleccionado o valor de la cookie o valor por defecto
/* $orderBy = $_GET['orderBy'] ?? $_COOKIE['orderBy'] ?? 'date_desc'; */
$orderConfig = $orderMappings[$orderBy] ?? ['column' => 'p.data', 'direction' => 'DESC'];

// Modificar la consulta SQL para incluir artículos
$sql = "SELECT p.*, 
        a.id as article_id, 
        a.title as article_title, 
        a.content as article_content,
        a.user_id as article_user_id
        FROM partits p 
        LEFT JOIN articles a ON p.id = a.match_id";

if (isset($_SESSION['loggedin']) && isset($_GET['lliga'])) {
    $sql .= " WHERE p.liga_id = (SELECT id FROM lligues WHERE nom = :lliga)";
    if ($_SESSION['loggedin']) {
        $sql .= " AND (a.user_id = :user_id OR a.user_id IS NULL)";
    }
}

// Obtenir partits de la lliga seleccionada
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $equipFavorit = $_SESSION['equip'] ?? null;
    if ($equipFavorit === 'pendiente' || $equipFavorit === null) {
        header('Location: ' . BASE_URL . 'preferences');
        exit;
    }
    $partits = getPartits($conn, $lligaSeleccionada, $partitsPerPage, $offset, $equipFavorit, $orderConfig['column'], $orderConfig['direction']);
} else {
    $partits = getPartits($conn, $lligaSeleccionada, $partitsPerPage, $offset, null, $orderConfig['column'], $orderConfig['direction']);
}

// Calcular total de partits per la paginació
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $totalPartits = getTotalPartits($conn, $lligaSeleccionada, $equipFavorit);
} else {
    $totalPartits = getTotalPartits($conn, $lligaSeleccionada);
}

$stmt = $conn->prepare($sql);
if (isset($_GET['lliga'])) {
    $stmt->bindParam(':lliga', $_GET['lliga']);
    if ($_SESSION['loggedin']) {
        $stmt->bindParam(':user_id', $_SESSION['userid']);
    }
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