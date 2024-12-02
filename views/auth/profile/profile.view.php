<?php
require_once BASE_PATH . 'controllers/utils/SessionHelper.php';
SessionHelper::checkLogin();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/auth/profile/profile.styles.css">
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
                    <img src="<?php echo !empty($_SESSION['avatar']) ? BASE_URL . 'uploads/avatars/' . $_SESSION['avatar'] : BASE_URL . 'assets/img/default-avatar.png'; ?>" 
                         class="current-avatar" id="current-avatar" alt="Avatar actual">
                    <div class="avatar-preview-container">
                        <img src="" class="avatar-preview" id="avatar-preview" alt="Preview" style="display: none;">
                    </div>
                </div>
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
                <label for="lliga">La teva lliga actual és la <?php echo $_SESSION['lliga']?>, vols canviar-la?</label>
                <select id="lliga" name="lliga" class="input-field" onchange="actualitzarEquips('registrar', '<?php echo isset($_SESSION['equip']) ? $_SESSION['equip'] : '' ?>', '<?php echo isset($_SESSION['lliga']) ? $_SESSION['lliga'] : '' ?>')" required>
                    <option value="">-- Selecciona la teva lliga --</option>
                    <option value="LaLiga">LaLiga</option>
                    <option value="Premier League">Premier League</option>
                    <option value="Ligue 1">Ligue 1</option>
                </select>
            </div>

            <div class="form-group">
                <label for="equip">El teu equip favorit és <?php echo $_SESSION['equip']?>, vols canviar?</label>
                <select id="equip" name="equip" class="input-field" required>
                    <option value="">-- Selecciona el teu equip favorit --</option>
                    <!-- Opcions d'equips seran afegides dinàmicament amb JavaScript -->
                </select>
            </div>

            <button type="submit">Guardar Canvis</button>
        </form>
        <a href="<?php echo BASE_URL; ?>" class="btn-tornar">Tornar enrere</a>
    </div>
</body>
</html>