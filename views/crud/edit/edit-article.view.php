<?php
require_once __DIR__ . "/../../../models/env.php";
require_once BASE_PATH . '/controllers/session/session.controller.php';
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: " . BASE_URL);
    exit();
}

$article = getArticleById($_GET['id']);
if ($article['user_id'] != $_SESSION['user_id']) {
    header("Location: " . BASE_URL);
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Article</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/main/styles.css">
</head>
<body>
    <div class="container">
        <h1>Editar Article</h1>
        <form action="<?php echo BASE_URL; ?>save-article" method="POST">
            <input type="hidden" name="match_id" value="<?php echo $match['id']; ?>">
            
            <div class="form-group">
                <label for="article_title">Títol:</label>
                <input type="text" id="article_title" name="article_title" required 
                       value="<?php echo htmlspecialchars($article['title'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="article_content">Contingut:</label>
                <textarea id="article_content" name="article_content" required rows="10"><?php 
                    echo htmlspecialchars($article['content'] ?? ''); 
                ?></textarea>
            </div>

            <div class="buttons-section">
                <button type="submit" class="btn-submit">Guardar Article</button>
                <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar</a>
            </div>
        </form>
    </div>
</body>
</html>