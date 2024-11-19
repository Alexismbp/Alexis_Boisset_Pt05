<?php
// Alexis Boisset

session_start();

try {
    require "../model/db_conn.php";
    require "../model/porra.php";

    try {
        $conn = connect(); 
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error de connexió: " . $e->getMessage();
        header("Location: ../view/eliminar.php?error=connexio"); 
        exit();
    }

    // Comprovar si la connexió és correcta i si la petició és POST
    if ($conn && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $partit_id = $_POST["partit_id"]; 

        // Validar que l'ID no estigui buit i sigui numèric
        if (!empty($partit_id) && is_numeric($partit_id)) {
            $resultat = deletePartit($conn, $partit_id); // Crida la funció per eliminar el partit
        } else {
            $_SESSION['error'] = "ID de partit no vàlid";
            header("Location: ../view/eliminar.php?error=id_invalid"); // Error: ID no vàlid
            exit();
        }

        // Comprovar si la eliminació ha estat correcta
        if ($resultat) {
            $_SESSION['success'] = "Partit eliminat correctament";
            header("Location: ../view/eliminar.php?success"); // Redirigeix amb èxit
            exit();
        } else {
            $_SESSION['error'] = "Error al eliminar el partit";
            header("Location: ../view/eliminar.php?error=eliminar"); // Error al eliminar
            exit();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && $conn) {
        $partit_id = $_GET["id"]; // S'assegura que l'ID del partit ve del index.view

        // Validar que l'ID no estigui buit i sigui numèric
        if (!empty($partit_id) && is_numeric($partit_id)) {
            $resultat = deletePartit($conn, $partit_id); // Crida la funció per eliminar el partit
        } else {
            $_SESSION['error'] = "ID de partit no vàlid";
            header("Location: ../view/eliminar.php?error=id_invalid"); // Error: ID no vàlid
            exit();
        }

        if ($resultat) {
            $_SESSION['success'] = "Partit eliminat correctament";
            header("Location: ../view/eliminar.php?success"); // Redirigeix amb èxit
            exit();
        } else {
            $_SESSION['error'] = "Error al eliminar el partit";
            header("Location: ../view/eliminar.php?error=eliminar"); // Error al eliminar
            exit();
        }
    } else {
        $_SESSION['error'] = "Petició no vàlida";
        header("Location: ../view/eliminar.php?error=peticio_no_valida"); // Error: Petició no vàlida
        exit();
    }
} catch (Exception $e) {
    $_SESSION['error'] = "S'ha produit un error: " . $e->getMessage();
    header("Location: ../view/eliminar.php?error=excepcio"); // Redirigeix en cas de fallida amb excepció
    exit();
}
