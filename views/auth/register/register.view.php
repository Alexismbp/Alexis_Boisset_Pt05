<?php
//Alexis Boisset
session_start();
require_once BASE_PATH . 'controllers/utils/form.controller.php';

// Netejar camps
if (isset($_GET['netejar']) && $_GET['netejar'] == true) {
    FormController::clearFormFields(['username', 'email', 'lliga', 'equip']);
}
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrar-se</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/auth/register/styles_register.css">
    <!-- Enllaç al arxiu JavaScript per carregar els <option> del <select id="equip"> -->
    <script src="<?php echo BASE_URL; ?>scripts/lligaequip.js" defer></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body onload="actualitzarEquips('registrar', '<?php echo $_SESSION['equip'] ?>')">
    <div class="container">
        <h1>Enregistrar-se</h1>

        <form action="<?php echo BASE_URL; ?>register" method="POST">
            <!-- FEEDBACK -->
            <?php include_once BASE_PATH . "views/layouts/feedback.view.php"?> 

            <label for="username">Nom d'usuari:</label>
            <input type="text" id="username" name="username" class="input-field" value="<?php echo SessionHelper::getFormValue('username'); ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" class="input-field" value="<?php echo $_SESSION['email'] ?>" required>

            <!-- Select per la Lliga -->
            <label for="lliga">Lliga on juga el teu equip favorit:</label>
            <select id="lliga" name="lliga" class="input-field" onchange="actualitzarEquips('registrar', '<?php echo $_SESSION['equip'] ?>', '<?php echo $_SESSION['lliga'] ?>')" onload="actualitzarEquips('registrar', '<?php echo $_SESSION['equip'] ?>', '<?php echo $_SESSION['lliga'] ?>')" required>
                <option value="">-- Selecciona la teva lliga --</option>
                <option value="LaLiga" <?php if ($_SESSION['lliga'] == 'LaLiga') echo 'selected'; ?>>LaLiga</option>
                <option value="Premier League" <?php if ($_SESSION['lliga'] == 'Premier League') echo 'selected'; ?>>Premier League</option>
                <option value="Ligue 1" <?php if ($_SESSION['lliga'] == 'Ligue 1') echo 'selected'; ?>>Ligue 1</option>
            </select>

            <!-- Select per l'Equip favorit -->
            <label for="equip">Equip favorit:</label>
            <select id="equip" name="equip" class="input-field" required>
                <option value="">-- Selecciona el teu equip favorit --</option>
                <!-- Opcions d'equips seran afegides dinàmicament amb JavaScript -->
            </select>

            <label for="password">Contrasenya:</label>
            <input type="password" id="password" name="password" class="input-field" required>

            <label for="password_confirm">Torna a introduir la contrasenya:</label>
            <input type="password" id="password_confirm" name="password_confirm" class="input-field" required>

            <div class="form-group captcha-container">
                <?php echo ReCaptchaController::renderCaptcha(); ?>
            </div>

            <input type="submit" class="btn-submit" value="Enregistrar-se">
        </form>

        <a href="<?php echo FormController::getClearFormUrl(); ?>" class="btn-back">Netejar</a>
        <br>
        <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar a la pàgina principal</a>
    </div>
</body>

</html>