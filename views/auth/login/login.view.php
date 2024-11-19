<!-- Alexis Boisset -->
<?php
session_start();

// Netejar variable de sessió "email" y tornar a Index.php
if (isset($_GET['back']) && $_GET['back'] == true) {
    unset($_SESSION['email']);
    header("Location:" . BASE_URL);
}
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logar-se</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/auth/login/styles_login.css">
</head>

<body>
    <div class="container">
        <h1>Logar-se</h1>
        <?php if (isset($_SESSION['session_ended']) && $_SESSION['session_ended'] == true): ?>
            <h3>Sessió expirada</h3>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>controllers/auth/login.controller.php" method="POST">
            
            <?php include BASE_PATH . 'views/layouts/errors.view.php'; ?>

            <label for="email">Correu electrònic:</label>
            <input type="email" id="email" name="email" class="input-field" value="<?php echo $_SESSION['email']; ?>" required>

            <label for="password">Contrasenya:</label>
            <input type="password" id="password" name="password" class="input-field" required>

            <input type="submit" class="btn-submit" value="Logar-se">
        </form>
        <a href="forgotpassword.view.php" class="btn-back">Has oblidat la contrasneya?</a>
        <br>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?back=true" class="btn-back">Tornar enrere</a>
    </div>
</body>

</html>
