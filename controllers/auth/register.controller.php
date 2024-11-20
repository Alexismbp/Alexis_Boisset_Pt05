<?php
session_start();

require_once __DIR__ . '/../../models/database/database.model.php';
require_once __DIR__ . '/../../models/user/user.model.php';
require_once __DIR__ . '/../utils/validation.controller.php';
require_once __DIR__ . '/../../models/env.php';

try {

    // DEBUG
    /* $_POST['username'] = 'Hola';
    $_POST['password'] = 'Admin123';
    $_POST['password_confirm'] = 'Admin123';
    $_POST['email'] = 'hola@gmail.com';
    $_POST['equip'] = 'FC Barcelona';
    $_SERVER['REQUEST_METHOD'] = 'POST'; */

    $missatgesError = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitizar entradas
        $nomUsuari = Validation::sanitizeInput($_POST['username']);
        $contrasenya = Validation::sanitizeInput($_POST['password']);
        $passwordConfirm = Validation::sanitizeInput($_POST['password_confirm']);
        $email = Validation::sanitizeInput($_POST['email']);
        $equipFavorit = Validation::sanitizeInput($_POST['equip']);

        // Validar campos
        $errors = array_filter([
            Validation::validateUsername($nomUsuari),
            Validation::validatePassword($contrasenya, $passwordConfirm),
            Validation::validateEmail($email),
            Validation::validateTeam($equipFavorit)
        ]);

        if (!empty($errors)) {
            $missatgesError = array_merge($missatgesError, $errors);
            throw new Exception();
        }

        // Encriptar contraseña
        $contrasenyaHashed = password_hash($contrasenya, PASSWORD_DEFAULT);

        // Registrar usuario
        if (registerUser($nomUsuari, $email, $contrasenyaHashed, $equipFavorit, $conn)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $nomUsuari;
            $_SESSION['equip'] = $equipFavorit;
            $_SESSION['lliga'] = getLeagueName($equipFavorit, $conn);
            $_SESSION['success'] = "Usuari registrat correctament";

            header("Location: " . BASE_URL);
            exit();
        } else {
            $missatgesError[] = "Aquest correu electrònic ja s'està utilitzant";
            throw new Exception();
        }
    }
} catch (Throwable $th) {
    $_SESSION['failure'] = empty($th->getMessage()) ? null : "Hi ha hagut un error: " . $th->getMessage();
    $_SESSION['errors'] = $missatgesError;
    $_SESSION['username'] = $nomUsuari ?? '';
    $_SESSION['email'] = $email ?? '';
    $_SESSION['lliga'] = isset($equipFavorit) ? getLeagueName($equipFavorit, $conn) : '';
    $_SESSION['equip'] = $equipFavorit ?? '';
} finally {
    header("Location: " . BASE_URL . "register");
    exit();
}
