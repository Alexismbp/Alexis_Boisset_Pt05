<?php
session_start();

if (isset($_SESSION['success'])) {
    echo '<div class="message success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['failure'])) {
    echo '<div class="message error">' . $_SESSION['failure'] . '</div>';
    unset($_SESSION['failure']);
}

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
    </div>
</body>
</html>