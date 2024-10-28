<!-- Alexis Boisset -->
<?php
session_start();

// Netejar variable de sessió "email" y tornar a Index.php
if (isset($_GET['back']) && $_GET['back'] == true) {
    unset($_SESSION['email']);
    header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logar-se</title>
    <link rel="stylesheet" href="styles/styles_login.css">
</head>

<body>
    <div class="container">
        <h1>Logar-se</h1>
        <?php if (isset($_SESSION['session_ended']) && $_SESSION['session_ended'] = true): ?>
            <h3>Sessió expirada</h3>
        <?php endif ?>


        <form action="../controlador/login.controller.php" method="POST">
            <!-- FEEDBACK -->
            <?php
            if (isset($_SESSION['failure'])) {
                echo '<div class="message error">' . $_SESSION['failure'] . '</div>';
                unset($_SESSION['failure']);
            } elseif (isset($_SESSION['success'])) {
                echo '<div class="message success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            ?>

            <label for="email">Correu electrònic:</label>
            <input type="email" id="email" name="email" class="input-field" value="<?php echo $_SESSION['email']; ?>" required>

            <label for="password">Contrasenya:</label>
            <input type="password" id="password" name="password" class="input-field" required>

            <input type="submit" class="btn-submit" value="Logar-se">
        </form>
        <a href="./forgotpassword.view.php" class="btn-back">Has oblidat la contrasneya?</a> <!-- Redirecció a form per enviar email -->
        <br>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?back=true" class="btn-back">Tornar enrere</a> <!-- Botó per netejar els camps del formulari i tornar enrere -->
    </div>
</body>

</html>