<?php
require_once BASE_PATH . 'models/utils/article.model.php';

try {
$articleData = getSharedArticleData($token);
    
    if (!$result) {
        throw new Exception('Enlace no válido o expirado');
    }

    // Filtrar contenido según permisos
    if (!$result['show_title']) {
        $result['title'] = null;
    }
    if (!$result['show_content']) {
        $result['content'] = null;
    }

    include BASE_PATH . 'views/shared/shared-article.view.php';
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ' . BASE_URL);
    exit;
}
