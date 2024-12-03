
<?php
require_once __DIR__ . "/../../models/env.php";
require_once BASE_PATH . "models/database/database.model.php";
require_once BASE_PATH . "models/utils/porra.model.php";
require_once BASE_PATH . '/controllers/utils/SessionHelper.php';

class EditMatchController {
    private $conn;
    private $errors = [];

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = Database::getInstance();
    }

    public function handleRequest($id) {
        if (!is_numeric($id)) {
            $_SESSION['failure'] = "L'ID ha de ser numèric";
            header("Location: " . BASE_URL);
            exit();
        }

        try {
            // Obtener datos del partido
            $partit = consultarPartido($this->conn, $id);
            if (!$partit) {
                $_SESSION['failure'] = "Aquest partit no existeix";
                header("Location: " . BASE_URL);
                exit();
            }

            // Obtener datos del artículo asociado
            $article = getArticleByMatchId($this->conn, $id);

            // Guardar datos en sesión para la vista
            SessionHelper::setSessionData([
                'equip_local' => getTeamName($this->conn, $partit['equip_local_id']),
                'equip_visitant' => getTeamName($this->conn, $partit['equip_visitant_id']),
                'data' => $partit['data'],
                'gols_local' => $partit['gols_local'],
                'gols_visitant' => $partit['gols_visitant'],
                'article_title' => $article['title'] ?? '',
                'article_content' => $article['content'] ?? ''
            ]);

            include BASE_PATH . "views/crud/edit/match-edit.view.php";
        } catch (PDOException $e) {
            $_SESSION['failure'] = "Error: " . $e->getMessage();
            header("Location: " . BASE_URL);
            exit();
        }
    }
}

// Iniciar el controlador
if (isset($router)) {
    $router->get('/edit-match/{id}', function() use ($router) {
        $id = $router->getParam('id');
        $controller = new EditMatchController();
        $controller->handleRequest($id);
    });
}
?>