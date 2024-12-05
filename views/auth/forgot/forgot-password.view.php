<?php
// Alexis Boisset

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once BASE_PATH . 'views/layouts/feedback.view.php';

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contrasenya</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/auth/forgot/styles_forgot.css">
</head>
<body>
    <div class="container">
        <h1>Recupera la teva contrasenya</h1>

        <p>Introdueix el teu e-mail i t'envíarem un correu per introduir una nova contrasenya</p>
        <form action="<?php echo BASE_URL; ?>forgotpassword" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>">
            <br>
            <input type="submit" value="Enviar">
        </form>

        <a href="<?php echo BASE_URL; ?>login" class="btn-back">Tornar enrere</a>
    </div>
</body>
</html>