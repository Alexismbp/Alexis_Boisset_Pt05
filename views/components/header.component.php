<header>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/styles/search.css">
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <div class="user-menu">
            <p>Benvingut, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</p>
            <div class="admin-profile">
                <img src="<?php echo !empty($_SESSION['avatar']) ? BASE_URL . 'uploads/avatars/' . $_SESSION['avatar'] : BASE_URL . 'assets/img/default-avatar.webp'; ?>"
                    class="header-avatar" alt="Avatar" onclick="toggleAdminMenu()">
                <div class="admin-dropdown" id="adminMenu">
                    <a href="<?php echo BASE_URL; ?>create-match">Crear nou partit</a>
                    <a href="<?php echo BASE_URL; ?>changepassword">Canviar contrasenya</a>
                    <a href="<?php echo BASE_URL; ?>profile">Editar Perfil</a>
                    <?php if ($_SESSION['userid'] == 1): ?>
                        <a href="<?php echo BASE_URL; ?>manage-users">Gestionar Usuarios</a>
                    <?php endif; ?>
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
    <div class="search-container">
        <input type="text" id="searchBar" placeholder="Cercar partits...">
        <div id="searchResults"></div>
        <!-- Contenedor para historial de búsquedas -->
        <div id="searchHistory" class="search-history"></div>
    </div>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/search.css">
    <meta name="base-url" content="<?php echo BASE_URL; ?>">
    <script src="<?php echo BASE_URL; ?>scripts/search.js" defer></script>
</header>

<script>
    function toggleAdminMenu() {
        const menu = document.getElementById('adminMenu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('click', function(e) {
        const menu = document.getElementById('adminMenu');
        const avatar = document.querySelector('.header-avatar');
        if (avatar && !avatar.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = 'none';
        }
    });
</script>