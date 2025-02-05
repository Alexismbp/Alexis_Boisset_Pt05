<?php
// Alexis Boisset
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/utils/article.model.php';
require_once BASE_PATH . 'models/utils/porra.model.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = Database::getInstance();

        // Validar datos del formulario
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
        $matchId = filter_input(INPUT_POST, 'match_id', FILTER_SANITIZE_NUMBER_INT);
        $userId = $_SESSION['userid'];

        // Verificar si ya existe un artículo para este partido y usuario
        if (articleExistsForMatchAndUser($conn, $matchId, $userId)) {
            $_SESSION['failure'] = "Ya tienes un artículo registrado para este partido.";
            header("Location: " . BASE_URL . "shared-articles");
            exit();
        }

        // Insertar el nuevo artículo
        if (insertArticle($conn, $matchId, $title, $content, $userId)) {
            // Eliminar el artículo compartido
            $token = $_GET['token'] ?? '';
            if (!empty($token)) {
                deleteSharedArticle($conn, $token);
            }

            $_SESSION['success'] = "Artículo registrado correctamente.";
        } else {
            throw new Exception("Error al registrar el artículo");
        }
    } catch (Exception $e) {
        $_SESSION['failure'] = "Error: " . $e->getMessage();
    }

    header("Location: " . BASE_URL . "shared-articles");
    exit();
}
