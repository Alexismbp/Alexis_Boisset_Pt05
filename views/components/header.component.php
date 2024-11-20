
<header>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <p>Benvingut, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</p>
        <a href="<?php echo BASE_URL; ?>logout" class="btn-logout">Tancar sessió</a>
    <?php else: ?>
        <a href="<?php echo BASE_URL; ?>login" class="btn-login">Logar-se</a>
        <a href="<?php echo BASE_URL; ?>register" class="btn-register">Enregistrar-se</a>
    <?php endif; ?>
</header>