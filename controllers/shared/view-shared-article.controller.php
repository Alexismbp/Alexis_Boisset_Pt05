<?php
require_once BASE_PATH . 'models/utils/article.model.php';

// Limpiar el buffer de salida
if (ob_get_length()) {
    ob_clean();
}

try {
    // Obtener el token de la URL
    $token = isset($_GET['token']) ? $_GET['token'] : null;

    if (!$token) {
        throw new Exception('Token no proporcionado.');
    }

    $articleData = getSharedArticleData($token);

    if (!$articleData) {
        throw new Exception('Enlace no válido o expirado.');
    }

    // Filtrar contenido según permisos
    if (!$articleData['show_title']) {
        $articleData['title'] = null;
    }
    if (!$articleData['show_content']) {
        $articleData['content'] = null;
    }

    // Retornar los datos en formato JSON si es una solicitud AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($articleData);
        exit;
    }

    // Incluir la vista si no es una solicitud AJAX
    include BASE_PATH . 'views/shared/shared-article.view.php';
} catch (Exception $e) {
    // Manejar errores y retornar un mensaje JSON o redireccionar
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    } else {
        $_SESSION['error'] = $e->getMessage();
        header('Location: ' . BASE_URL);
    }
    exit;
}
