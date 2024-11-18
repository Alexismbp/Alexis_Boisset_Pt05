<?php
// Alexis Boisset
try {
    session_start();

    require "../model/db_conn.php";
    require "../model/user_model.php";

    // Connexió a BD
    try {
        $conn = connect();
    } catch (PDOException $e) {
        die("Error de connexió: " . $e->getMessage());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $conn) {
        // Rebre dades formulari
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        // Obtenir dades d'usuari per utilitzar en cas de login successful (+info a la funcio al model)
        if ($userData = getUserData($email, $conn)) {

            $idUsuari = $userData['id'];
            $nomUsuari = $userData['nom_usuari'];
            $hashedPassword = $userData['contrasenya']; // Contrasenya de la BD (hashejada/encriptada)
            $equip = $userData['equip_favorit'];

            // Usar password_verify para verificar la contraseña ingresada
            if (password_verify($password, $hashedPassword)) {

                $_SESSION['LAST_ACTIVITY'] = time(); // Començo time() per poder expirar la sessió en cas d'absencia

                // Agafo/assigno dades esencials per el funcionament i estética de l'aplicació
                $_SESSION['loggedin'] = true;
                $_SESSION['userid'] = $idUsuari;
                $_SESSION['username'] = $nomUsuari;
                $_SESSION['equip'] = $equip;
                $_SESSION['lliga'] = getLeagueName($equip, $conn);

                header("Location: ../index.php");
                exit();
            } else {
                $_SESSION['failure'] = "La contrasenya no es correcta"; // Si la contrasenya no coincideix dona error
                $_SESSION['email'] = $email;
            }
        } else {
            $_SESSION['failure'] = "L'usuari no existeix a la base de dades"; // Si la funció retorna fals dona error (no existeix l'usuari)
        }
    }
} catch (\Throwable $th) {
    $_SESSION['failure'] = "Error: " . $th->getMessage();
} finally {
    header("Location: ../view/login.view.php");
    exit();
}
