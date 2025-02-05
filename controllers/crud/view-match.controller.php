<?php
require_once BASE_PATH . "models/database/database.model.php";
require_once BASE_PATH . "models/utils/porra.model.php";
require_once BASE_PATH . '/controllers/utils/SessionHelper.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get match ID from router parameter
if (isset($router)) {
    $id = $router->getParam('id');

    if (!is_numeric($id)) {
        $_SESSION['failure'] = "ID de partit no vàlid";
        header("Location: " . BASE_URL);
        exit();
    }

    try {
        $conn = Database::getInstance();

        // Get match data
        $partit = consultarPartido($conn, $id);
        $partit['equip_local'] = getTeamName($conn, $partit['equip_local_id']);
        $partit['equip_visitant'] = getTeamName($conn, $partit['equip_visitant_id']);

        if (!$partit) {
            $_SESSION['failure'] = "Aquest partit no existeix";
            header("Location: " . BASE_URL);
            exit();
        } elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && isset($_SESSION['equip'])) { // Comprovar si está loguejat i té equip
            if ($_SESSION['equip'] != $partit['equip_local'] && $_SESSION['equip'] != $partit['equip_visitant']) {
                $_SESSION['failure'] = "No tens permisos per veure aquest partit";
                header("Location: " . BASE_URL);
                exit();
            }
        }

        // Get article data
        $article = getArticleByMatchId($conn, $id);

        // Store match and article data in session





        // Include the view
        include BASE_PATH . 'views/crud/view/match-view.view.php';
    } catch (PDOException $e) {
        $_SESSION['failure'] = "Error al carregar el partit: " . $e->getMessage();
        header("Location: " . BASE_URL);
        exit();
    }
}
