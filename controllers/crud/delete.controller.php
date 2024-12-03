<?php
require_once __DIR__ . "/../../models/env.php";
require_once BASE_PATH . "models/database/database.model.php";
require_once BASE_PATH . "models/utils/porra.model.php";

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $conn = Database::getInstance();

    if ($conn && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $partit_id = $_POST["partit_id"];

        if (!empty($partit_id) && is_numeric($partit_id)) {
            $resultat = deletePartit($conn, $partit_id);
            
            if ($resultat) {
                $_SESSION['success'] = "Partit eliminat correctament";
                header("Location: " . BASE_URL);
                exit();
            } else {
                $_SESSION['error'] = "Error al eliminar el partit";
                header("Location: " . BASE_URL);
                exit();
            }
        } else {
            $_SESSION['error'] = "ID de partit no vàlid";
            header("Location: " . BASE_URL);
            exit();
        }
    } else {
        $_SESSION['error'] = "Petició no vàlida";
        header("Location: " . BASE_URL);
        exit();
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: " . BASE_URL);
    exit();
}
?>
