
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['success'])) {
    echo '<div class="message success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['failure'])) {
    echo '<div class="message error">' . $_SESSION['failure'] . '</div>';
    unset($_SESSION['failure']);
}

$token = $_GET['token'] ?? '';
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablir Contrasenya</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/auth/styles_reset_password.css">
</head>
<body>
    <div class="container">
        <h1>Restablir Contrasenya</h1>
        <form action="<?php echo BASE_URL; ?>controllers/auth/reset-password.controller.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label for="new_password">Nova Contrasenya:</label>
            <input type="password" id="new_password" name="new_password" class="input-field" required>
            <label for="confirm_password">Confirma la Nova Contrasenya:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="input-field" required>
            <input type="submit" class="btn-submit" value="Restablir Contrasenya">
        </form>
        <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar a la pàgina principal</a>
    </div>
</body>
</html>