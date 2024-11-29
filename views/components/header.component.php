<header>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <div class="user-menu">
            <p>Benvingut, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</p>
            <div class="dropdown">
                <button class="dropbtn" onclick="toggleDropdown()">Menu Administració ▼</button>
                <div class="dropdown-content" id="dropdown-content">
                    <a href="<?php echo BASE_URL; ?>partido/crear">Crear nou partit</a>
                    <a href="<?php echo BASE_URL; ?>equipo/gestionar">Gestionar equips</a>
                    <a href="<?php echo BASE_URL; ?>liga/gestionar">Gestionar lligues</a>
                    <a href="<?php echo BASE_URL; ?>changepassword">Canviar contrasenya</a>
                    <a href="<?php echo BASE_URL; ?>logout">Log out</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <a href="<?php echo BASE_URL; ?>login" class="btn-login">Logar-se</a>
        <a href="<?php echo BASE_URL; ?>register" class="btn-register">Enregistrar-se</a>
    <?php endif; ?>
</header>