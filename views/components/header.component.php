<header>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/styles/search.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/main/styles.css">
    <a href="<?= BASE_URL ?>"><img src="<?php echo BASE_URL; ?>assets/img/football_forum_logo.png" alt="Logo" class="logo" width="50" height="50" style="margin-left: 10px;"></a>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <div class="user-menu">
            <p>Benvingut, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</p>
            <div class="admin-profile">
                <!-- Avatar de l'usuari -->
                <img src="<?php echo !empty($_SESSION['avatar']) ? BASE_URL . 'uploads/avatars/' . $_SESSION['avatar'] : BASE_URL . 'assets/img/default-avatar.webp'; ?>"
                    class="header-avatar" alt="Avatar" onclick="toggleAdminMenu()">
                <!-- Desplegable de menú d'usuari -->
                <div class="admin-dropdown" id="adminMenu">
                    <a href="<?php echo BASE_URL; ?>create-match">Crear nou partit</a>
                    <a href="<?php echo BASE_URL; ?>shared-articles">Partits compartits</a>
                    <a href="<?php echo BASE_URL; ?>changepassword">Canviar contrasenya</a>
                    <a href="<?php echo BASE_URL; ?>profile">Editar Perfil</a>
                    <a href="<?php echo BASE_URL; ?>teams">Equipos</a>
                    <?php if ($_SESSION['userid'] == 1): ?>
                        <!-- Si l'usuari és l'administrador (id de l'usuari administrador és 1), mostrar enllaç per gestionar usuaris -->
                        <a href="<?php echo BASE_URL; ?>manage-users">Gestionar usuaris</a>
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
    <!-- Barra de cerca -->
    <div class="search-container">
        <input type="text" id="searchBar" placeholder="Cercar partits...">
        <div id="searchResults"></div>
        <!-- Contenedor para historial de búsquedas -->
        <div id="searchHistory" class="search-history"></div>
    </div>
    <meta name="base-url" content="<?php echo BASE_URL; ?>">
    <script src="<?php echo BASE_URL; ?>scripts/search.js" defer></script>
</header>

<script>
    // Funció per desplegable de menú d'usuari
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