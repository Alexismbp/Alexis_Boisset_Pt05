<?php
require_once BASE_PATH . 'models/utils/porra.model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== true || !isset($_SESSION['userid'])) {
    $_SESSION['failure'] = "No tens permisos per editar aquest partit (Que intentas Xavi?)";
    header("Location: " . BASE_URL);
    exit();
}

// Obtener el ID del partido desde la URL usando Router
$id = basename($_SERVER['REQUEST_URI']);

if (!$id || !is_numeric($id)) {
    header("Location: " . BASE_URL);
    exit();
}

// Obtener datos del partido
$conn = Database::getInstance();
$partit = consultarPartido($conn, $id);

// Pasar a una dada que sigui HUMAN READABLE (B2 English)
$partit['equip_local'] = getTeamName($conn, $partit['equip_local_id']);
$partit['equip_visitant'] = getTeamName($conn, $partit['equip_visitant_id']);


// Obtener datos del artículo asociado
$article = getArticleByMatchId($conn, $id);

if ($partit) {
    if ($_SESSION['equip'] != $partit['equip_local'] && $_SESSION['equip'] != $partit['equip_visitant']) {
        $_SESSION['failure'] = "No tens permisos per veure aquest partit";
        header("Location: " . BASE_URL);
        exit();
    }
} else if (!$partit) {
    $_SESSION['failure'] = "No s'ha trobat cap article associat a aquest partit";
    header("Location: " . BASE_URL);
    exit();
}

if ($article) {
    if ($_SESSION['userid'] != $article['user_id']) {
        $_SESSION['failure'] = "No tens permisos per editar aquest partit";
        header("Location: " . BASE_URL);
        exit();
    }
}

// Guardar datos en sesión para el formulario
dadesEdicio($conn, $partit, $article, $id);

// Obtenim els noms y dades dels equips y partits per a mostrar-los
function dadesEdicio($conn, $partit, $article, $id)
{
    $_SESSION['equip_local'] = $partit['equip_local'];
    $_SESSION['equip_visitant'] = $partit['equip_visitant'];
    $_SESSION['data'] = $partit['data'];
    $_SESSION['gols_local'] = $partit['gols_local'];
    $_SESSION['gols_visitant'] = $partit['gols_visitant'];
    $_SESSION['jugat'] = $partit['jugat'];
    $_SESSION["id"] = $id;
    $_SESSION['editant'] = true;
    $_SESSION['article_title'] = $article ? $article['title'] : '';
    $_SESSION['article_content'] = $article ? $article['content'] : '';

    return true;
}
