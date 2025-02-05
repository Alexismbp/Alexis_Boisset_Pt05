<?php

/**
 * Controlador per gestionar la visualització i edició d'articles compartits
 * 
 * Aquest controlador permet:
 * - Visualitzar articles compartits mitjançant un token
 * - Editar i crear nous articles basats en articles compartits
 * - Gestionar les peticions AJAX per obtenir informació dels articles
 */

require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/utils/article.model.php';
require_once BASE_PATH . 'models/shared_article.model.php';

// Iniciar sessió si no està iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Gestió de la creació d'articles a partir d'un article compartit
 * 
 * Processa el formulari POST quan un usuari vol crear un nou article
 * basat en un article compartit. Realitza les següents validacions:
 * - Comprova l'autenticació de l'usuari
 * - Valida el token de compartició
 * - Verifica que no existeixi un article duplicat
 * - Gestiona la creació de l'article mitjançant una transacció
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'edit') {
    // Obtenir l'ID de l'usuari de la sessió actual
    $userId = $_SESSION['userid'] ?? null;
    if (!$userId) {
        $_SESSION['error'] = "No estas autenticat.";
        header('Location: ' . BASE_URL);
        exit;
    }

    // Obtenir i validar el token de la URL
    $token = $_GET['token'] ?? null;
    if (!$token) {
        $_SESSION['error'] = "Token no proporcionat.";
        header('Location: ' . BASE_URL);
        exit;
    }

    // Crear instància del model i obtenir dades de l'article compartit
    $sharedArticle = new SharedArticle();
    $articleData = $sharedArticle->getSharedArticleByToken($token);

    if (!$articleData) {
        $_SESSION['error'] = "Enllaç no vàlid o expirat.";
        header('Location: ' . BASE_URL);
        exit;
    }

    // Netejar espais en blanc de les dades del formulari
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($content)) {
        $_SESSION['error'] = "El títol i el contingut són obligatoris.";
        header("Location: " . BASE_URL . "shared/{$token}?action=edit");
        exit;
    }

    // Verificar si ja existeix un article amb el mateix títol per aquest usuari
    if ($sharedArticle->checkDuplicateArticle($articleData['match_id'], $title, $userId)) {
        $_SESSION['error'] = "Aquest article ja està donat d'alta.";
        header("Location: " . BASE_URL . "shared/{$token}?action=edit");
        exit;
    }

    try {
        // Crear l'article utilitzant una transacció per garantir la integritat de les dades
        $sharedArticle->createUserArticleWithTransaction(
            $articleData['match_id'],
            $userId,
            $title,
            $content,
            $token
        );

        $_SESSION['success'] = "Article donat d'alta correctament";
        header("Location: " . BASE_URL);
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: " . BASE_URL . "shared/{$token}?action=edit");
    }
    exit;
}


// Netejar el buffer de sortida per evitar problemes amb la redirecció
if (ob_get_length()) {
    ob_clean();
}

/**
 * Gestió de la visualització d'articles compartits
 * 
 * Permet:
 * - Visualitzar articles compartits mitjançant un token
 * - Respondre a peticions AJAX retornant les dades en format JSON
 * - Gestionar errors i mostrar missatges apropriats
 */
try {
    // $token = $_GET['token'] ?? null;

    if (!$token) {
        throw new Exception('Token no proporcionado.');
    }

    $shared = getSharedArticleData($token);

    if (!$shared) {
        throw new Exception('Enlace no válido o expirado.');
    }

    // Detectar si és una petició AJAX per retornar JSON en lloc d'HTML
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($shared);
        exit;
    }

    // Si no és AJAX, carregar la vista normal
    include BASE_PATH . 'views/shared/shared-article.view.php';
} catch (Exception $e) {
    // Gestionar errors de manera diferent per peticions AJAX i normals
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    } else {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['error'] = $e->getMessage();
        header('Location: ' . BASE_URL);
    }
    exit;
}
