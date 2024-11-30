<?php
if (!isset($_SESSION['needs_preferences'])) {
    header('Location: ' . BASE_URL);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Selecciona les teves preferències</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/auth/preferences/styles_preferences.css">
    <script src="<?php echo BASE_URL; ?>scripts/lligaequip.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Abans de continuar...</h1>
        <p>Si us plau, selecciona la teva lliga i equip favorit:</p>

        <form action="<?php echo BASE_URL; ?>save-preferences" method="POST">
            <?php include_once BASE_PATH . "views/layouts/feedback.view.php"?> 

            <div class="form-group">
                <label for="lliga">Lliga Favorita:</label>
                <select id="lliga" name="lliga" class="input-field" onchange="actualitzarEquips('registrar')" required>
                    <option value="">-- Selecciona la teva lliga --</option>
                    <option value="LaLiga">LaLiga</option>
                    <option value="Premier League">Premier League</option>
                    <option value="Ligue 1">Ligue 1</option>
                </select>
            </div>

            <div class="form-group">
                <label for="equip">Equip Favorit:</label>
                <select id="equip" name="equip" class="input-field" required>
                    <option value="">-- Selecciona el teu equip favorit --</option>
                </select>
            </div>

            <input type="submit" class="btn-submit" value="Guardar Preferències">
        </form>
        <a href="<?php echo BASE_URL; ?>logout" class="btn-cancel">Cancel·lar</a>
    </div>
</body>
</html>