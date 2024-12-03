<header>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <div class="user-menu">
            <p>Benvingut, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</p>
            <div class="admin-profile">
                <img src="<?php echo !empty($_SESSION['avatar']) ? BASE_URL . 'uploads/avatars/' . $_SESSION['avatar'] : BASE_URL . 'assets/img/default-avatar.webp'; ?>" 
                     class="header-avatar" alt="Avatar" onclick="toggleAdminMenu()">
                <div class="admin-dropdown" id="adminMenu">
                    <a href="<?php echo BASE_URL; ?>partido/crear">Crear nou partit</a>
                    <a href="<?php echo BASE_URL; ?>equipo/gestionar">Gestionar equips</a>
                    <a href="<?php echo BASE_URL; ?>liga/gestionar">Gestionar lligues</a>
                    <a href="<?php echo BASE_URL; ?>changepassword">Canviar contrasenya</a>
                    <a href="<?php echo BASE_URL; ?>profile">Editar Perfil</a>
                    <a href="<?php echo BASE_URL; ?>logout">Tancar sessió</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="auth-buttons">
            <a href="<?php echo BASE_URL; ?>login" class="btn-login">Logar-se</a>
            <a href="<?php echo BASE_URL; ?>register" class="btn-register">Enregistrar-se</a>
        </div>
    <?php endif; ?>
</header>

<script>
function toggleAdminMenu() {
    const menu = document.getElementById('adminMenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(e) {
    const menu = document.getElementById('adminMenu');
    const avatar = document.querySelector('.header-avatar');
    if (!avatar.contains(e.target) && !menu.contains(e.target)) {
        menu.style.display = 'none';
    }
});
</script>