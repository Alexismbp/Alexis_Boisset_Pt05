<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../../models/env.php";
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/utils/porra.model.php';
require_once BASE_PATH . '/controllers/session/session.controller.php';
require_once BASE_PATH . 'controllers/utils/form.controller.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: " . BASE_URL);
    exit();
}

// Obtener el ID del partido desde la URL usando Router
$id = basename($_SERVER['REQUEST_URI']);

if (!$id || !is_numeric($id)) {
    header("Location: " . BASE_URL);
    exit();
}

// Obtener datos del partido
$conn = Database::getInstance();
$stmt = consultarPartido($conn, $id);
$partit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$partit) {
    $_SESSION['failure'] = "Partit no trobat";
    header("Location: " . BASE_URL);
    exit();
}

// Guardar datos en sesión para el formulario
dadesEdicio($conn, $partit, $id);

// Obtenim els noms y dades dels equips y partits per a mostrar-los
function dadesEdicio($conn, $partit, $id)
{
    // Pasar a una dada que sigui HUMAN READABLE (B2 English)
    $equip_local_name = isset($partit['equip_local_id'])  ? getTeamName($conn, $partit['equip_local_id']) : '';
    $equip_visitant_name = isset($partit['equip_visitant_id']) ? getTeamName($conn, $partit['equip_visitant_id']) : '';

    $_SESSION['equip_local'] = $equip_local_name;
    $_SESSION['equip_visitant'] = $equip_visitant_name;
    $_SESSION['data'] = $partit['data'];
    $_SESSION['gols_local'] = $partit['gols_local'];
    $_SESSION['gols_visitant'] = $partit['gols_visitant'];
    $_SESSION['jugat'] = $partit['jugat'];
    $_SESSION["id"] = $id;
    $_SESSION['editant'] = true;
    $_SESSION['lliga'] = getLeagueNameByTeam($equip_local_name,$conn);

    return true;
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
        <form action="<?php echo BASE_URL; ?>save-match" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <?php require_once BASE_PATH . '/views/layouts/feedback.view.php'; ?>
            
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