<?php
//Alexis Boisset
session_start();

// Netejar camps
if (isset($_GET['netejar']) && $_GET['netejar'] == true) {
    unset($_SESSION['username']);
    unset($_SESSION['email']);
    unset($_SESSION['lliga']);
    unset($_SESSION['equip']);
}
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrar-se</title>
    <link rel="stylesheet" href="styles/styles_register.css">
    <!-- Enllaç al arxiu JavaScript per carregar els <option> del <select id="equip"> -->
    <script src="../scripts/lligaequip.js" defer></script>
</head>

<body onload="actualitzarEquips('registrar', '<?php echo $_SESSION['equip'] ?>')">
    <div class="container">
        <h1>Enregistrar-se</h1>

        <form action="../controlador/register.controller.php" method="POST">
            <!-- FEEDBACK -->
            <?php
            if (isset($_SESSION['success'])) {
                echo '<div class="message success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            } elseif (isset($_SESSION['failure'])) {
                echo '<div class="message error">' . $_SESSION['failure'] . '</div>';
                unset($_SESSION['failure']);
            }
            if (isset($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo '<div class="message error">' . $error . '</div>';
                }
                $edit = true;
                unset($_SESSION['errors']);
            }
            ?>

            <label for="username">Nom d'usuari:</label>
            <input type="text" id="username" name="username" class="input-field" value="<?php echo $_SESSION['username'] ?>" required>

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

            <input type="submit" class="btn-submit" value="Enregistrar-se">
        </form>

        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?netejar=true" class="btn-back">Netejar</a> <!-- Boto per netejar camps formulari -->
        <br>
        <a href="../../index.php" class="btn-back">Tornar a la pàgina principal</a>
    </div>
</body>

</html>