<?php
// Alexis Boisset
require_once BASE_PATH . 'controllers/utils/SessionHelper.php';

// Comprovar si l'usuari està autenticat
SessionHelper::checkLogin();
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/auth/profile/styles_profile.css">
    <script src="<?php echo BASE_URL; ?>scripts/lligaequip.js" defer></script>
</head>

<body>

    <div class="profile-container">
        <h2>Editar Perfil</h2>

        <?php include BASE_PATH . "views/layouts/feedback.view.php"; ?>

        <form action="<?php echo BASE_URL; ?>save-profile" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="avatar">Avatar:</label>
                <div class="avatar-container">
                    <img src="<?php echo isset(($_SESSION['avatar'])) ? BASE_URL . 'uploads/avatars/' . $_SESSION['avatar'] : BASE_URL . 'assets/img/default-avatar.webp'; ?>"
                        class="current-avatar" id="current-avatar" alt="Avatar actual">
                    <div class="avatar-preview-container">
                        <img src="" class="avatar-preview" id="avatar-preview" alt="Preview" style="display: none;">
                    </div>
                </div>
                <!-- Acceptar només arxius d'imatge i mostrar previsualització en petit -->
                <input type="file" id="avatar" name="avatar" accept="image/*" onchange="previewImage(this);">
            </div>

            <div class="form-group">
                <label for="username">Nom d'usuari:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Correu electrònic:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="lliga">La teva lliga actual és la <?php echo $_SESSION['lliga'] ?>, vols canviar-la?</label>
                <select id="lliga" name="lliga" class="input-field" onchange="actualitzarEquips('profile', '<?php echo $_SESSION['equip']; ?>')" required>
                    <option value="">-- Selecciona la teva lliga --</option>
                    <option value="LaLiga" <?php echo ($_SESSION['lliga'] == 'LaLiga') ? 'selected' : ''; ?>>LaLiga</option>
                    <option value="Premier League" <?php echo ($_SESSION['lliga'] == 'Premier League') ? 'selected' : ''; ?>>Premier League</option>
                    <option value="Ligue 1" <?php echo ($_SESSION['lliga'] == 'Ligue 1') ? 'selected' : ''; ?>>Ligue 1</option>
                </select>
            </div>

            <div class="form-group">
                <label for="equip">El teu equip favorit és <?php echo $_SESSION['equip'] ?>, vols canviar?</label>
                <select id="equip" name="equip" class="input-field" required>
                    <option value="<?php echo $_SESSION['equip']; ?>" selected><?php echo $_SESSION['equip']; ?></option>
                    <!-- Opcions d'equips seran afegides dinàmicament amb JavaScript -->
                </select>
            </div>

            <button type="submit">Guardar Canvis</button>
        </form>
        <!-- Nova secció integrada per la gestió de l'API Key -->
        <div class="api-key-group">
            <label for="api_key">La teva API Key</label>
            <input type="text" id="api_key" name="api_key" readonly value="<?php echo isset($_SESSION['api_key']) ? $_SESSION['api_key'] : 'Ninguna'; ?>">
            <form method="POST" action="<?php echo BASE_URL; ?>save-profile">
                <button type="submit" name="generate_api_key" class="btn-generate">Generar API Key</button>
            </form>
        </div>
        <a href="<?php echo BASE_URL; ?>/" class="btn-tornar">Tornar enrere</a>
    </div>
</body>

</html>