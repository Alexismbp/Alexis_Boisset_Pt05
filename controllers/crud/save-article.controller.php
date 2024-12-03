
<?php
require_once __DIR__ . "/../../models/env.php";
require_once BASE_PATH . "models/database/database.model.php";

if (!isset($_SESSION['loggedin'])) {
    header("Location: " . BASE_URL);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $match_id = $_POST['match_id'];
    $title = trim($_POST['article_title']);
    $content = trim($_POST['article_content']);
    $user_id = $_SESSION['userid'];

    $sql = "INSERT INTO articles (match_id, user_id, title, content) 
            VALUES (:match_id, :user_id, :title, :content)
            ON DUPLICATE KEY UPDATE title = :title, content = :content";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':match_id' => $match_id,
            ':user_id' => $user_id,
            ':title' => $title,
            ':content' => $content
        ]);
        
        $_SESSION['success'] = "Article guardat correctament!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al guardar l'article";
    }
}

header("Location: " . BASE_URL);
exit;