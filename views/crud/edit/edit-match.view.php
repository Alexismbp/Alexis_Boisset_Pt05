
<?php
session_start();
require_once __DIR__ . "/../../../models/env.php";
require_once BASE_PATH . '/controllers/session/session.controller.php';
require_once BASE_PATH . 'controllers/utils/form.controller.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: " . BASE_URL);
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL);
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Partit</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/crud/create/styles_crear.css">
</head>
<body>
    <div class="container">
        <h1>Editar Partit</h1>
        <form action="../../controllers/crud/update-match.controller.php" method="POST">
            <?php require_once BASE_PATH . '/views/layouts/feedback.view.php'; ?>
            
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            
            <div class="form-group">
                <label for="lliga">Lliga:</label>
                <input type="text" id="lliga" name="lliga" class="input-field" 
                       value="<?php echo $_SESSION['lliga']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="equip_local">Equip Local:</label>
                <input type="text" id="equip_local" name="equip_local" class="input-field" 
                       value="<?php echo $_SESSION['equip_local']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="equip_visitant">Equip Visitant:</label>
                <input type="text" id="equip_visitant" name="equip_visitant" class="input-field" 
                       value="<?php echo $_SESSION['equip_visitant']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="data">Data del Partit:</label>
                <input type="date" id="data" name="data" class="input-field" 
                       value="<?php echo $_SESSION['data']; ?>">
            </div>

            <div class="form-group">
                <label for="gols_local">Gols Local:</label>
                <input type="number" id="gols_local" name="gols_local" class="input-field" 
                       value="<?php echo $_SESSION['gols_local']; ?>">
            </div>

            <div class="form-group">
                <label for="gols_visitant">Gols Visitant:</label>
                <input type="number" id="gols_visitant" name="gols_visitant" class="input-field" 
                       value="<?php echo $_SESSION['gols_visitant']; ?>">
            </div>

            <div class="article-section">
                <div class="form-group">
                    <label for="article_title">Títol de l'Article:</label>
                    <input type="text" id="article_title" name="article_title" class="input-field" 
                           value="<?php echo $_SESSION['article_title'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="article_content">Contingut de l'Article:</label>
                    <textarea id="article_content" name="article_content" class="input-field article-content"><?php 
                        echo $_SESSION['article_content'] ?? ''; 
                    ?></textarea>
                </div>
            </div>

            <div class="buttons-section">
                <button type="submit" class="btn-submit">Actualitzar Partit</button>
                <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar enrere</a>
            </div>
        </form>
    </div>
</body>
</html>