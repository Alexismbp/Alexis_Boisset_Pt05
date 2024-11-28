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
        <?php if (isset($_SESSION['session_ended']) && $_SESSION['session_ended'] == true): ?>
            <h3>Sessió expirada</h3>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>login" method="POST">
            <?php include BASE_PATH . 'views/layouts/feedback.view.php'; ?>

            <div class="form-group">
                <label for="email">Correu electrònic:</label>
                <input type="email" id="email" name="email" class="input-field" value="<?php echo SessionHelper::getFormValue('email'); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Contrasenya:</label>
                <input type="password" id="password" name="password" class="input-field" required>
            </div>

            <?php if (SessionHelper::needsCaptcha()): ?>
                <div class="form-group captcha-container">
                    <?php echo ReCaptchaController::renderCaptcha(); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <input type="submit" class="btn-submit" value="Logar-se">
            </div>
        </form>
        <a href="<?php echo BASE_URL; ?>forgotpassword" class="btn-back">Has oblidat la contrasenya?</a>
        <br>
        <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar enrere</a>
    </div>
</body>

</html>
