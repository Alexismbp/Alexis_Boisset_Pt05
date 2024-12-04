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
        $stmt = consultarPartido($conn, $id);
        $partit = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$partit) {
            $_SESSION['failure'] = "Aquest partit no existeix";
            header("Location: " . BASE_URL);
            exit();
        }

        // Get article data
        $article = getArticleByMatchId($conn, $id);

        // Store match and article data in session
        
         $partit['equip_local'] = getTeamName($conn, $partit['equip_local_id']);
         $partit['equip_visitant'] = getTeamName($conn, $partit['equip_visitant_id']);
      
        

        // Include the view
        include BASE_PATH . 'views/crud/view/match-view.view.php';

    } catch (PDOException $e) {
        $_SESSION['failure'] = "Error al carregar el partit: " . $e->getMessage();
        header("Location: " . BASE_URL);
        exit();
    }
}
?>