<?php
// Alexis Boisset
try {
    session_start();
    $missatgesError = [];
    require '../model/db_conn.php';
    require '../model/user_model.php';

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $conn = connect()) {

        // Agafa les dades del formulari y les formata correctament
        $nomUsuari = validate($_POST['username']);
        $contrasenya = validate($_POST['password']);
        $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
        $passwordConfirm = validate($_POST['password_confirm']);
        $email = validate($_POST['email']);
        $equipFavorit = validate($_POST['equip']);
        $error = false;

        // Validació de camps
        if (empty($nomUsuari)) {
            $missatgesError[] = "El nom d'usuari no pot estar buit";
            $error = true;
        }

        if (empty($contrasenya)) {
            $missatgesError[] = "És obligatori una contrasenya";
            $error = true;
        } elseif (preg_match($passwordPattern, $contrasenya) === 0) {
            $missatgesError[] = "La contrasenya ha de tenir mínim: 8 caràcters, una majúscula, una minúscula i un dígit";
            $error = true;
        }

        if (empty($email)) {
            $missatgesError[] = "És obligatori un correu electrònic";
            $error = true;
        }

        if (empty($equipFavorit)) {
            $missatgesError[] = "És obligatori un equip favorit";
            $error = true;
        }

        if ($contrasenya !== $passwordConfirm) {
            $missatgesError[] = "Les contrasenyes no coincideixen";
            $error = true;
        }

        if ($error) {
            throw new Exception();
        }

        // Encript de la contrasenya
        $contrasenyaHashed = password_hash($contrasenya, PASSWORD_DEFAULT);

        // Registrar usuari
        if (registerUser($nomUsuari, $email, $contrasenyaHashed, $equipFavorit, $conn)) {

            // Asignar valores a la sesión
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $nomUsuari;
            $_SESSION['equip'] = $equipFavorit;
            $_SESSION['lliga'] = getLeagueName($equipFavorit, $conn);
            $_SESSION['success'] = "Usuari registrat correctament";

            // Redireccionar a la pàgina d'inici (prefereixo això que haver de tornar a logar-me)
            header("Location: ../index.php");
            exit();
        } else {
            $missatgesError[] = "Aquest correu electrònic ja s'està utilitzant";
            throw new Exception();
        }
    }
} catch (Throwable $th) {
    // Si falla assignem les dades a $_SESSION per recuperar-les al formulari
    $_SESSION['failure'] = empty($th->getMessage()) ? null : "Hi ha hagut un error: " . $th->getMessage();
    $_SESSION['errors'] = $missatgesError;
    $_SESSION['username'] = $nomUsuari;
    $_SESSION['email'] = $email;
    $_SESSION['lliga'] = getLeagueName($equipFavorit, $conn);
    $_SESSION['equip'] = $equipFavorit;
} finally {
    header("Location: ../view/register.view.php");
    exit();
}

// Funció per prevenir entrades no desitjades de dades o injects
function validate($data)
{
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    return $data;
}
