<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin'])) {
    header('Location: login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canviar Contrasenya</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/auth/change/styles_change.css">
</head>
<body>
    <div class="container">
        <h2>
            <?php if (isset($_SESSION['oauth_user']) && $_SESSION['oauth_user'] == 0): ?>
                Afegir Contrasenya al Compte
            <?php else: ?>
                Canviar Contrasenya
            <?php endif; ?>
        </h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <form action="<?php echo BASE_URL; ?>changepassword" method="POST">
            <?php if (!isset($_SESSION['oauth_user'])): ?>
                <div class="form-group">
                    <label for="current_password">Contrasenya Actual:</label>
                    <input type="password" name="current_password" required>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="new_password">Nova Contrasenya:</label>
                <input type="password" name="new_password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmar Nova Contrasenya:</label>
                <input type="password" name="confirm_password" required>
            </div>
            
            <button type="submit">
                <?php echo isset($_SESSION['oauth_user']) ? 'Afegir Contrasenya' : 'Canviar Contrasenya'; ?>
            </button>
        </form>
        <a href="<?php echo BASE_URL; ?>" class="btn-tornar">Tornar enrere</a>
    </div>
</body>
</html>