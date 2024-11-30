<!-- Alexis Boisset -->
<?php
require_once BASE_PATH . '/controllers/utils/SessionHelper.php';
require_once BASE_PATH . '/controllers/utils/ReCaptchaController.php';
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logar-se</title>
    <link rel="stylesheet" href="<?php echo BASE_URL?>views/auth/login/styles_login.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <div class="container">
        <h1>Logar-se</h1>

        <form action="<?php echo BASE_URL; ?>login" method="POST">
            <?php include BASE_PATH . 'views/layouts/feedback.view.php'; ?>

            <div class="form-group">
                <label for="email">Correu electrònic:</label>
                <input type="email" id="email" name="email" class="input-field" value="<?php echo isset($_SESSION['remembered_email']) ? $_SESSION['remembered_email'] : SessionHelper::getFormValue('email'); ?>" required> 
            </div>

            <div class="form-group">
                <label for="password">Contrasenya:</label>
                <input type="password" id="password" name="password" class="input-field" required>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember_me" id="remember_me"> Recorda'm
                </label>
            </div>

            <?php if (SessionHelper::needsCaptcha()): ?>
                <div class="form-group captcha-container">
                    <?php echo ReCaptchaController::renderCaptcha(); ?>
                </div>
            <?php endif; ?>

            
            <input type="submit" class="btn-submit" value="Logar-se">
            
        </form>
        
        <div class="oauth-buttons">
            <a href="<?php echo BASE_URL; ?>oauth/google" class="btn-google">
                <img src="<?php echo BASE_URL; ?>assets/img/google-icon.png" alt="Google Icon">
                Iniciar sesión con Google
            </a>
            <a href="<?php echo BASE_URL; ?>oauth/github" class="btn-github">
                <img src="<?php echo BASE_URL; ?>assets/img/github-icon.png" alt="GitHub Icon">
                Iniciar sesión con GitHub
            </a>
        </div>

        <a href="<?php echo BASE_URL; ?>forgotpassword" class="btn-back">Has oblidat la contrasenya?</a>
        <br>
        <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar enrere</a>
    </div>
</body>

</html>
