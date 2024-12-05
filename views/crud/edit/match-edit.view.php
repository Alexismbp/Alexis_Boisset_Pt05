<?php
// Alexis Boisset
// Vista per editar un partit. Només es pot editar si l'usuari està autenticat.
require_once BASE_PATH . '/controllers/session/session.controller.php';
require_once BASE_PATH . 'controllers/crud/edit-match.controller.php';
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
        <form action="<?php echo BASE_URL; ?>save-match" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['id']); ?>">
            <?php require_once BASE_PATH . '/views/layouts/feedback.view.php'; ?>

            <div class="form-group">
                <label for="lliga">Lliga:</label>
                <input type="text" id="lliga" name="lliga" class="input-field"
                    value="<?php echo htmlspecialchars($_SESSION['lliga']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="equip_local">Equip Local:</label>
                <input type="text" id="equip_local" name="equip_local" class="input-field"
                    value="<?php echo htmlspecialchars($_SESSION['equip_local']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="equip_visitant">Equip Visitant:</label>
                <input type="text" id="equip_visitant" name="equip_visitant" class="input-field"
                    value="<?php echo htmlspecialchars($_SESSION['equip_visitant']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="data">Data del Partit:</label>
                <input type="date" id="data" name="data" class="input-field"
                    value="<?php echo htmlspecialchars($_SESSION['data']); ?>">
            </div>

            <div class="form-group">
                <label for="gols_local">Gols Local:</label>
                <input type="number" id="gols_local" name="gols_local" class="input-field"
                    value="<?php echo htmlspecialchars($_SESSION['gols_local']); ?>" min="0">
            </div>

            <div class="form-group">
                <label for="gols_visitant">Gols Visitant:</label>
                <input type="number" id="gols_visitant" name="gols_visitant" class="input-field"
                    value="<?php echo htmlspecialchars($_SESSION['gols_visitant']); ?>" min="0">
            </div>

            <div class="article-section">
                <div class="form-group">
                    <label for="article_title">Títol de l'Article:</label>
                    <input type="text" id="article_title" name="article_title" class="input-field"
                        value="<?php echo htmlspecialchars($_SESSION['article_title'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="article_content">Contingut de l'Article:</label>
                    <textarea id="article_content" name="article_content" class="input-field article-content"><?php echo htmlspecialchars($_SESSION['article_content'] ?? ''); ?></textarea>
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