<?php
require_once BASE_PATH . 'models/database/database.model.php';

function handleSharedArticle($token) {
    try {
        $pdo = Database::getInstance();
        
        // Obtener información del artículo compartido
        $stmt = $pdo->prepare("
            SELECT a.*, m.*, sa.show_title, sa.show_content 
            FROM shared_articles sa
            JOIN articles a ON sa.article_id = a.id
            JOIN partits m ON sa.match_id = m.id
            WHERE sa.token = ?
        ");
        
        $stmt->execute([$token]);
        $shared = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$shared) {
            // Manejar enlace inválido o expirado
            header("HTTP/1.0 404 Not Found");
            require BASE_PATH . 'views/errors/404.view.php';
            exit();
        }

        // Cargar la vista con los datos
        require BASE_PATH . 'views/shared/shared-article.view.php';

    } catch (Exception $e) {
        // Manejar error
        error_log($e->getMessage());
        header("HTTP/1.0 500 Internal Server Error");
        echo "Error interno del servidor";
    }
}

// Obtener el token de la URL y manejar la solicitud
$token = $router->getParam('token');
handleSharedArticle($token);
