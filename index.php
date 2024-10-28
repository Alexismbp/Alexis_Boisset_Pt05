<!-- Alexis Boisset -->
<?php
require "controlador/config.php"; // Fitxer per detectar innactivitat
require "./model/db_conn.php";

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

$conn = connect();

// Determinar la página actual
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $partitsPerPage;

// Consulta SQL segons si l'usuari esta logat o no, per mostrar uns partits o tots respectivament
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $equipFavorit = $_SESSION['equip'];
    $sql = "SELECT p.id, p.data, e_local.nom AS equip_local, e_visitant.nom AS equip_visitant, p.gols_local, p.gols_visitant, p.jugat, l.nom AS lliga
            FROM partits p
            JOIN equips e_local ON p.equip_local_id = e_local.id
            JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
            JOIN lligues l ON p.liga_id = l.id
            WHERE (e_local.nom = :equip OR e_visitant.nom = :equip)
            AND l.nom = :lliga
            LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':equip', $equipFavorit, PDO::PARAM_STR);
    $stmt->bindValue(':lliga', $lligaSeleccionada, PDO::PARAM_STR);
} else {
    $sql = "SELECT p.id, p.data, e_local.nom AS equip_local, e_visitant.nom AS equip_visitant, p.gols_local, p.gols_visitant, p.jugat, l.nom AS lliga
            FROM partits p
            JOIN equips e_local ON p.equip_local_id = e_local.id
            JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
            JOIN lligues l ON p.liga_id = l.id
            WHERE l.nom = :lliga
            LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':lliga', $lligaSeleccionada, PDO::PARAM_STR);
}

$stmt->bindValue(':limit', $partitsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$partits = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Calcular total de partits per la paginació
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    // Consulta per saber quants partits juga l'equip favorit
    $totalPartitsStmt = $conn->prepare("SELECT COUNT(*) 
                                        FROM partits p
                                        JOIN equips e_local ON p.equip_local_id = e_local.id
                                        JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
                                        JOIN lligues l ON p.liga_id = l.id
                                        WHERE (e_local.nom = :equip OR e_visitant.nom = :equip)
                                        AND l.nom = :lliga");
    $totalPartitsStmt->bindValue(':equip', $equipFavorit, PDO::PARAM_STR);
    $totalPartitsStmt->bindValue(':lliga', $lligaSeleccionada, PDO::PARAM_STR);
} else {
    // Consulta per saber quantitat total de partits
    $totalPartitsStmt = $conn->prepare("SELECT COUNT(*) 
                                        FROM partits p
                                        JOIN lligues l ON p.liga_id = l.id
                                        WHERE l.nom = :lliga");
    $totalPartitsStmt->bindValue(':lliga', $lligaSeleccionada, PDO::PARAM_STR);
}

$totalPartitsStmt->execute();
$totalPartits = $totalPartitsStmt->fetchColumn();

$totalPages = ceil($totalPartits / $partitsPerPage);

include "./vista/index.vista.php";
?>