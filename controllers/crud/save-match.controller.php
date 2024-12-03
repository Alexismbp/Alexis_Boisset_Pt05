<?php
require_once __DIR__ . "/../../models/env.php";
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/utils/porra.model.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Connexió a la base de dades
try {
    Database::getInstance();
} catch (PDOException $e) {
    die("Error de connexió: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $conn) {
    $id = $_POST['id'] ?? null;
    // Obtenim i netegem les dades del formulari
    /* $id = htmlspecialchars($_POST["id"] ?? null); */
    $equip_local = htmlspecialchars($_POST["equip_local"]);
    $equip_visitant = htmlspecialchars($_POST["equip_visitant"]);
    $data = htmlspecialchars($_POST["data"]);
    $gols_local = $_POST["gols_local"] ?? null;
    $gols_visitant = $_POST["gols_visitant"] ?? null;
    $missatgesError = [];
    $error = false;

    $gols_local = $gols_local === "" ? null : $gols_local;
    $gols_visitant = $gols_visitant === "" ? null : $gols_visitant;


    // Comprobar correcta inserció de valors als camps
    if (empty($equip_local)) {
        $missatgesError[] = 'L\'equip local no pot estar buit';
        $error = true;
    }

    if (empty($equip_visitant)) {
        $missatgesError[] = 'L\'equip visitant no pot estar buit';
        $error = true;
    }

    if (empty($data)) {
        $missatgesError[] = 'La data no pot estar buida';
        $error = true;
    }

    if (is_numeric($gols_local) && is_numeric($gols_visitant)) {
        $missatgesError[] = "Els gols han de ser númerics";
        $error = true;
    }

    // Verificació de l'ID
    if (!empty($id) && !is_numeric($id)) {
        $missatgesError[] = 'L\'ID ha de ser numèric';
        $error = true;
    } elseif (!empty($id)) {
        // Consulta el partit per editar
        $resultat = consultarPartido($conn, $id);
        $partit = $resultat->fetch(PDO::FETCH_ASSOC);

        if (dadesEdicio($conn, $partit, $id)) {

            header("Location: " . BASE_URL . "views/crud/edit/match-edit.view.php");
            exit();
        } else {
            $missatgesError[] = "Aquest partit no existeix";
            $error = true;
        }
    }

    // Si hi ha errors, redirigeix amb errors
    if ($error) {
        SessionHelper::setSessionData([
            'equip_local' => $equip_local,
            'equip_visitant' => $equip_visitant,
            'data' => $data,
            'gols_local' => $gols_local,
            'gols_visitant' => $gols_visitant,
            'errors' => $missatgesError
        ]);
        header("Location: " . BASE_URL . "views/crud/edit/match-edit.view.php");
        exit();
    }

    // Obté valors adaptats a la base de dades
    $equip_local = getTeamID($conn, $equip_local);
    $equip_visitant = getTeamID($conn, $equip_visitant);
    
    // Validar que los equipos existen
    if (!$equip_local || !$equip_visitant) {
        $_SESSION['failure'] = "Els equips seleccionats no són vàlids";
        header("Location: " . BASE_URL . "views/crud/edit/match-edit.view.php");
        exit();
    }

    // Inserció o actualització del partit
    try {
        if ($id && is_numeric($id)) {
            $stmt = updatePartido($conn, $id, $equip_local, $equip_visitant, $data, $gols_local, $gols_visitant);
            if ($stmt->execute()) {
                $_SESSION['success'] = "El partit s'ha actualitzat correctament!";
                // Limpiar variables de sesión de edición
                unset($_SESSION['id']);
                unset($_SESSION['editant']);
            }
        } else {
            $liga_id = getLigaID($conn, $equip_local);
            $stmt = insertPartido($conn, $equip_local, $equip_visitant, $liga_id, $data, $gols_local, $gols_visitant);
            if ($stmt->execute()) {
                $_SESSION['success'] = "El partit s'ha inserit correctament!";
            }
        }
        header("Location: " . BASE_URL);
        exit();
    } catch (PDOException $e) {
        $_SESSION['failure'] = "Error: " . $e->getMessage();
        header("Location: " . BASE_URL . "views/crud/edit/match-edit.view.php");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && $conn) {
    // SI LA ID ES REP PER GET (quan cliquem a editar partit desde l'index)
    $id = $_GET['id'];

    if (!empty($id) && !is_numeric($id)) {
        $missatgesError[] = 'L\'ID ha de ser numèric';
        $error = true;
    } elseif (!empty($id)) {
        // Consulta el partit per editar
        $resultat = consultarPartido($conn, $id);
        $partit = $resultat->fetch(PDO::FETCH_ASSOC);

        if (dadesEdicio($conn, $partit, $id)) {

            header("Location: " . BASE_URL . "views/crud/edit/match-edit.view.php");
            exit();
        } else {
            $missatgesError[] = "Aquest partit no existeix";
            $error = true;
        }
    }

    if ($error) {
        SessionHelper::setSessionData([
            'equip_local' => $equip_local,
            'equip_visitant' => $equip_visitant,
            'data' => $data,
            'gols_local' => $gols_local,
            'gols_visitant' => $gols_visitant,
            'errors' => $missatgesError
        ]);
        header("Location: " . BASE_URL . "views/crud/edit/match-edit.view.php");
        exit();
    }

    $_SESSION['errors'] = $missatgesError;

    header("Location: ../index.php");
    exit();
} else {
    $_SESSION['failure'] = "Alguna cosa no ha funcionat com s'esperava";
    header("Location: " . BASE_URL . "views/crud/edit/match-edit.view.php");
    exit();
}



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
