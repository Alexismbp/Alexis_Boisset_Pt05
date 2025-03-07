<?php
// Alexis Boisset
// Vista per crear un nou partit. Només es pot crear si l'usuari està autenticat.
require_once __DIR__ . "/../../../models/env.php";
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/utils/porra.model.php';
require_once BASE_PATH . '/controllers/session/session.controller.php';
require_once BASE_PATH . 'controllers/utils/form.controller.php';
require_once BASE_PATH . 'controllers/utils/SessionHelper.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Si l'usuari no està autenticat, se va pa casa.
SessionHelper::checkLogin();

// Netejar camps del formulari
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
    <div class="container form-container"> <!-- Cambiado de 'gestalt-form' a 'form-container' -->
        <h1>Crear Nou Partit</h1>
        <form action="<?php echo BASE_URL; ?>save-match" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <!-- Feedback -->
            <?php require_once BASE_PATH . '/views/layouts/feedback.view.php'; ?>

            <fieldset>
                <legend>Informació del Partit</legend>
                <div class="form-group">
                    <label for="lliga">Lliga:</label>
                    <input type="text" id="lliga" name="lliga" class="input-field"
                        value="<?php echo htmlspecialchars($_SESSION['lliga']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="equip_local">Equip Local:</label>
                    <input type="text" id="equip_local" name="equip_local" class="input-field"
                        value="<?php echo htmlspecialchars($_SESSION['equip']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="equip_visitant">Equip Visitant:</label>
                    <select id="equip_visitant" name="equip_visitant" class="input-field" required>
                        <option value="">-- Selecciona l'equip visitant --</option>
                    </select>
                </div>
            </fieldset>

            <fieldset>
                <legend>Resultat</legend>
                <div class="form-group">
                    <label for="data">Data del Partit:</label>
                    <input type="date" id="data" name="data" class="input-field">
                </div>

                <div class="form-group">
                    <label for="gols_local">Gols Local (Opcional):</label>
                    <input type="number" id="gols_local" name="gols_local" class="input-field" min="0" value="<?= isset($_SESSION['gols_local']) ? $_SESSION['gols_local'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="gols_visitant">Gols Visitant (Opcional):</label>
                    <input type="number" id="gols_visitant" name="gols_visitant" class="input-field" min="0">
                </div>
            </fieldset>

            <input type="hidden" name="user_id" value="<?php echo $_SESSION['userid']; ?>">

            <fieldset>
                <legend>Article</legend>
                <div class="article-section">
                    <div class="form-group">
                        <label for="article_title">Títol de l'Article (Opcional):</label>
                        <input type="text" id="article_title" name="article_title" class="input-field"
                            value="<?php echo isset($_GET['shared_title']) ? htmlspecialchars($_GET['shared_title']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="article_content">Contingut de l'Article (Opcional):</label>
                        <textarea id="article_content"
                            name="article_content"
                            class="input-field article-content">
                                  <?php echo isset($_GET['shared_content']) ? htmlspecialchars($_GET['shared_content']) : ''; ?></textarea>
                    </div>
                </div>
            </fieldset>

            <div class="buttons-section">
                <button type="submit" class="btn-submit">Crear Partit</button>
                <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar enrere</a>
            </div>
        </form>
    </div>
</body>

</html>