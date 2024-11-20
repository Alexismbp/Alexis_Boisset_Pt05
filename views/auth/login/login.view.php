<!-- Alexis Boisset -->
<?php

// Netejar variable de sessió "email" y tornar a Index.php

?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logar-se</title>
    <link rel="stylesheet" href="<?php echo BASE_URL?>views/auth/login/styles_login.css">
</head>

<body>
    <div class="container">
        <h1>Logar-se</h1>
        <?php if (isset($_SESSION['session_ended']) && $_SESSION['session_ended'] == true): ?>
            <h3>Sessió expirada</h3>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>login" method="POST">
            <?php include BASE_PATH . 'views/layouts/feedback.view.php'; ?>

            <label for="email">Correu electrònic:</label>
            <input type="email" id="email" name="email" class="input-field" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>

            <label for="password">Contrasenya:</label>
            <input type="password" id="password" name="password" class="input-field" required>

            <input type="submit" class="btn-submit" value="Logar-se">
        </form>
        <a href="<?php echo BASE_URL; ?>forgotpassword" class="btn-back">Has oblidat la contrasenya?</a>
        <br>
        <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar enrere</a>
    </div>
</body>

</html>
