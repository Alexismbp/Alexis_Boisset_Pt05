<?php
// Alexis Boisset
session_start();

require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/user/user.model.php';

try {
    $conn = connect();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        $userData = getUserData($email, $conn);

        if ($userData) {
            $hashedPassword = $userData['contrasenya'];

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['LAST_ACTIVITY'] = time();
                $_SESSION['loggedin'] = true;
                $_SESSION['userid'] = $userData['id'];
                $_SESSION['username'] = $userData['nom_usuari'];
                $_SESSION['equip'] = $userData['equip_favorit'];
                $_SESSION['lliga'] = getLeagueName($userData['equip_favorit'], $conn);

                header("Location: " . BASE_URL);
                exit();
            } else {
                $_SESSION['failure'] = "La contrasenya no és correcta";
                $_SESSION['email'] = $email;
            }
        } else {
            $_SESSION['failure'] = "L'usuari no existeix a la base de dades";
        }
    }
} catch (Exception $e) {
    $_SESSION['failure'] = "Error: " . $e->getMessage();
} finally {
    header("Location: " . BASE_URL . "login");
    exit();
}
