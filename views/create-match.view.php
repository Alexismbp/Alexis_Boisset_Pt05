<?php
// Alexis Boisset

require "../../private/controller/config.controller.php"; // Detecció de temps d'inactivitat

session_start(); // Inicia la sessió per a gestionar l'estat de l'usuari i les dades del formulari.

if (!isset($_SESSION['loggedin'])) {
    header("Location: " . BASE_URL);
    exit();
}
// Si s'ha fet click al boto "Netejar"
if (isset($_GET['netejar'])) {
    unset($_SESSION['id']);
    unset($_SESSION['equip_local']);
    unset($_SESSION['equip_visitant']);
    unset($_SESSION['data']);  // Neteja totes les variables de la sessió.
    unset($_SESSION['gols_local']);
    unset($_SESSION['gols_visitant']);
    unset($_SESSION['editant']);
}

// Comprova si s'està editant i estableix l'atribut $edit
$edit = (isset($_SESSION['editant'])) ? "readonly" : ""; // Si l'usuari està editant, estableix l'atribut $edit per evitar canvis.
?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear o Editar Partit</title>
    <link rel="stylesheet" href="../styles/styles_crear.css">

    <script src="../../private/scripts/lligaequip.js" defer></script>

</head>

<body>
    <div class="container">
        <h1>Crear o Editar Partit</h1>
        <form action="../../private/controller/save-match.controller.php" method="POST">
            <!-- FEEDBACK -->
            <?php
            // Mostra missatges d'èxit o error SI EXISTEIXEN.
            if (isset($_SESSION['success'])) {
                echo '<div class="message success">' . $_SESSION['success'] . '</div>'; // Missatge d'èxit.
                unset($_SESSION['success']); // Neteja el missatge de la sessió.
            } elseif (isset($_SESSION['failure'])) {
                echo '<div class="message error">' . $_SESSION['failure'] . '</div>'; // Missatge d'error.
                unset($_SESSION['failure']); // Neteja el missatge de la sessió.
            }

            // Mostra errors SI EXISTEIXEN.
            if (isset($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo '<div class="message error">' . $error . '</div>'; // Mostra cada error.
                }
                unset($_SESSION['errors']); // Neteja els errors de la sessió.
            }
            ?>
            <label for="id">ID del Partit (només per editar):</label>
            <input type="text" id="id" name="id" class="input-field" placeholder="<?php echo isset($_SESSION['id']) ? $_SESSION['id'] : ''; ?>" <?php echo $edit ?>> <!-- Input per l'ID del partit, només editable si no s'està editant. -->
            <?php if ($_SESSION['editant']) { ?>
                <label for="lliga">Lliga:</label>
                <input type="text" id="lliga" name="lliga" class="input-field" value="<?php echo $_SESSION['lliga']; ?>" placeholder="Selecciona la lliga" <?php echo $edit ?>> <!-- Input per l'equip local, només lectura. -->
                <label for="equip_local">Equip Local:</label>
                <input type="text" id="equip_local" name="equip_local" class="input-field" value="<?php echo $_SESSION['equip_local']; ?>" placeholder="Escriu el nom de l'equip local" <?php echo $edit ?>> <!-- Input per l'equip local, només lectura. -->
                <label for="equip_visitant">Equip Visitant:</label>
                <input type="text" id="equip_visitant" name="equip_visitant" class="input-field" value="<?php echo $_SESSION['equip_visitant']; ?>" placeholder="Escriu el nom de l'equip visitant" <?php echo $edit ?>> <!-- Input per l'equip visitant, només lectura. -->
            <?php } else { ?>
                <!-- Select per la Lliga -->
                <label for="lliga">Lliga:</label>
                <select id="lliga" name="lliga" class="input-field" onchange="actualitzarEquips('crear')">
                    <option value="">-- Selecciona la teva lliga --</option>
                    <option value="LaLiga">LaLiga</option>
                    <option value="Premier League">Premier League</option>
                    <option value="Ligue 1">Ligue 1</option>
                </select>
                <!-- Select per l'Equip favorit -->
                <label for="equip_local">Equip local:</label>
                <select id="equip_local" name="equip_local" class="input-field">
                    <option value="">-- Selecciona el teu equip favorit --</option>
                    <!-- Opcions d'equips seran afegides dinàmicament amb JavaScript -->
                </select>
                <label for="equip_visitant">Equip visitant:</label>
                <select id="equip_visitant" name="equip_visitant" class="input-field">
                    <option value="">-- Selecciona el teu equip favorit --</option>
                    <!-- Opcions d'equips seran afegides dinàmicament amb JavaScript -->
                </select>
            <?php } ?>
            <label for="data">Data del Partit:</label>
            <input type="date" id="data" name="data" class="input-field" value="<?php echo isset($_SESSION['data']) ? $_SESSION['data'] : ''; ?>"> <!-- Input per la data del partit. -->
            <label for="gols_local">Gols Local (Opcional):</label>
            <input type="number" id="gols_local" name="gols_local" class="input-field" value="<?php echo isset($_SESSION['gols_local']) ? $_SESSION['gols_local'] : ''; ?>"> <!-- Input per gols locals, opcional. -->
            <label for="gols_visitant">Gols Visitant (Opcional):</label>
            <input type="number" id="gols_visitant" name="gols_visitant" class="input-field" value="<?php echo isset($_SESSION['gols_visitant']) ? $_SESSION['gols_visitant'] : ''; ?>"> <!-- Input per gols visitants, opcional. -->
            <button type="submit" class="btn-submit">Guardar</button> <!-- Botó per guardar els canvis. -->
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?netejar=true" class="btn-back">Netejar</a> <!-- Botó per netejar els camps del formulari. -->
            <a href="../../index.php" class="btn-back">Tornar enrere</a> <!-- Botó per tornar a index.php -->
        </form>
    </div>
</body>

</html>