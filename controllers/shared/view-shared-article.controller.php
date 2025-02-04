<?php
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/utils/article.model.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Comprovem si és POST per actualitzar (donar d'alta l'article propi)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'edit') {
    session_start();
    // Suposant que l'id de l'usuari està emmagatzemat a $_SESSION['userid']
    $userId = $_SESSION['userid'] ?? null;
    if (!$userId) {
        $_SESSION['error'] = "No estas autenticat.";
        header('Location: ' . BASE_URL);
        exit;
    }

    $token = $_GET['token'] ?? null;
    if (!$token) {
        $_SESSION['error'] = "Token no proporcionat.";
        header('Location: ' . BASE_URL);
        exit;
    }

    // Recuperem les dades de l'article compartit
    $articleData = getSharedArticleData($token);
    if (!$articleData) {
        $_SESSION['error'] = "Enllaç no vàlid o expirat.";
        header('Location: ' . BASE_URL);
        exit;
    }

    // Recollida del formulari d'edició
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($content)) {
        $_SESSION['error'] = "El títol i el contingut són obligatoris.";
        header("Location: " . BASE_URL . "shared/{$token}?action=edit");
        exit;
    }

    // Comprovació de duplicats: en aquest exemple es comprova si ja existeix un article amb mateix match_id, títol i que pertanyi a l'usuari
    $conn = Database::getInstance();
    $dupStmt = $conn->prepare("SELECT id FROM articles WHERE match_id = ? AND title = ? AND user_id = ?");
    $dupStmt->execute([$articleData['match_id'], $title, $userId]);
    if ($dupStmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['error'] = "Aquest article ja està donat d'alta.";
        header("Location: " . BASE_URL . "shared/{$token}?action=edit");
        exit;
    }

    // Inserir l'article com a propi
    $insStmt = $conn->prepare("INSERT INTO articles (match_id, user_id, title, content, created_at) VALUES (?, ?, ?, ?, NOW())");
    if ($insStmt->execute([$articleData['match_id'], $userId, $title, $content])) {
        // Eliminar l'article compartit per evitar duplicats al llistat
        $delStmt = $conn->prepare("DELETE FROM shared_articles WHERE token = ?");
        $delStmt->execute([$token]);

        $_SESSION['success'] = "Article donat d'alta correctament";
        header("Location: " . BASE_URL); // Redirigir al índice principal
    } else {
        $_SESSION['error'] = "Error al donar d'alta l'article";
        header("Location: " . BASE_URL . "shared/{$token}?action=edit");
    }
    exit;
}

// Resta de la lògica original (per GET) i per peticions AJAX
// Limpiar el buffer de salida
if (ob_get_length()) {
    ob_clean();
}

try {
    // $token = $_GET['token'] ?? null;

    if (!$token) {
        throw new Exception('Token no proporcionado.');
    }

    $shared = getSharedArticleData($token); // Cambiar articleData por shared para coincidir con la vista

    if (!$shared) {
        throw new Exception('Enlace no válido o expirado.');
    }

    // Si es una solicitud AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($shared);
        exit;
    }

    // Incluir la vista
    include BASE_PATH . 'views/shared/shared-article.view.php';
} catch (Exception $e) {
    // Manejar errores y retornar un mensaje JSON o redireccionar
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    } else {
        session_start();
        $_SESSION['error'] = $e->getMessage();
        header('Location: ' . BASE_URL);
    }
    exit;
}
