<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contrasenya</title>
</head>
<body>
    <h1>Recupera la teva contrasenya</h1>

    <p>Introdueix el teu e-mail i t'envíarem un correu per introduir una nova contrasenya</p>
    <form action="../controlador/password-recover.controller.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>">
        <br>
        <input type="submit" value="Enviar">
    </form>
</body>
</html>