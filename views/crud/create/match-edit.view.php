<?php
session_start();
require_once __DIR__ . "/../../../models/env.php";
require_once BASE_PATH . '/controllers/session/session.controller.php';
require_once BASE_PATH . 'controllers/utils/form.controller.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: " . BASE_URL);
    exit();
}

if (isset($_GET['netejar'])) {
    FormController::clearFormFields(['equip_local', 'equip_visitant', 'data', 'gols_local', 'gols_visitant']);
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Partit</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/crud/create/styles_crear.css">
    <script src="<?php echo BASE_URL; ?>/scripts/lligaequip.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Crear Nou Partit</h1>
        <form action="<?php echo BASE_URL; ?>controllers/crud/save-match.controller.php" method="POST">
            <?php require_once BASE_PATH . '/views/layouts/feedback.view.php'; ?>
            
            <div class="form-group">
                <label for="lliga">Lliga:</label>
                <select id="lliga" name="lliga" class="input-field" onchange="actualitzarEquips('crear')">
                    <option value="">-- Selecciona la teva lliga --</option>
                    <option value="LaLiga">LaLiga</option>
                    <option value="Premier League">Premier League</option>
                    <option value="Ligue 1">Ligue 1</option>
                </select>
            </div>

            <div class="form-group">
                <label for="equip_local">Equip local:</label>
                <select id="equip_local" name="equip_local" class="input-field">
                    <option value="">-- Selecciona l'equip local --</option>
                </select>
            </div>

            <div class="form-group">
                <label for="equip_visitant">Equip visitant:</label>
                <select id="equip_visitant" name="equip_visitant" class="input-field">
                    <option value="">-- Selecciona l'equip visitant --</option>
                </select>
            </div>

            <div class="form-group">
                <label for="data">Data del Partit:</label>
                <input type="date" id="data" name="data" class="input-field">
            </div>

            <div class="form-group">
                <label for="gols_local">Gols Local (Opcional):</label>
                <input type="number" id="gols_local" name="gols_local" class="input-field">
            </div>

            <div class="form-group">
                <label for="gols_visitant">Gols Visitant (Opcional):</label>
                <input type="number" id="gols_visitant" name="gols_visitant" class="input-field">
            </div>

            <div class="article-section">
                <div class="form-group">
                    <label for="article_title">Títol de l'Article (Opcional):</label>
                    <input type="text" id="article_title" name="article_title" class="input-field">
                </div>
                <div class="form-group">
                    <label for="article_content">Contingut de l'Article (Opcional):</label>
                    <textarea id="article_content" name="article_content" class="input-field article-content"></textarea>
                </div>
            </div>

            <div class="buttons-section">
                <button type="submit" class="btn-submit">Crear Partit</button>
                <a href="<?php echo FormController::getClearFormUrl(); ?>" class="btn-back">Netejar</a>
                <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar enrere</a>
            </div>
        </form>
    </div>
</body>
</html>