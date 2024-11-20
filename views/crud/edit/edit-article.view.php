
<?php
require_once BASE_URL . '/controllers/session/session.controller.php';
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
        <form action="<?php echo BASE_URL; ?>/update-article" method="POST">
            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
            <div class="form-group">
                <label for="title">Títol:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Contingut:</label>
                <textarea id="content" name="content" required><?php echo htmlspecialchars($article['content']); ?></textarea>
            </div>
            <button type="submit">Actualitzar</button>
            <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar</a>
        </form>
    </div>
</body>
</html>