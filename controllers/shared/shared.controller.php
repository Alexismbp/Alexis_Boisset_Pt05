<?php
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/shared_article.model.php';

function handleSharedArticle($token)
{
    try {
        $sharedArticleModel = new SharedArticle();
        $shared = $sharedArticleModel->getSharedArticleByToken($token);

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
handleSharedArticle($token);
