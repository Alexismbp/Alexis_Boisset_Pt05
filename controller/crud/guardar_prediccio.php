<!-- WORK IN PROGRESS -->
<?php
//Alexis Boisset
try {
    session_start();
    require '../model/db_conn.php'; 
    require '../model/porra.php';
    
    
    if ($_SERVER['REQUEST_METHOD' === 'POST' && $conn = connect()]) {
        // Obtener los datos del formulario
        $partit_id = $_POST['partit_id'];
        $gols_local = $_POST['gols_local'];
        $gols_visitant = $_POST['gols_visitant'];
        $idUsuari = $_SESSION['userid'];
    
        // Guardar la predicción y redirigir
        if (guardarPrediccio($conn, $partit_id, $idUsuari, $gols_local, $gols_visitant)) {
            $_SESSION['success'] = "Predicció guardada correctament";
        } else {
            throw new Exception("Error al guardar la predicción.", 1);
             
        }
    }
} catch (\Throwable $th) {
    $_SESSION['errors'] = $th->getMessage();
} finally{
    header('Location: ../index.php'); // Redirigir después de guardar
    exit();
}
