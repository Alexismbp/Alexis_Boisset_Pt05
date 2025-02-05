<?php
$teamId = $router->getParam('id');
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jugadores del Equipo</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/main/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/teams/styles_teams.css">
    <script src="<?php echo BASE_URL; ?>scripts/players.js" defer></script>
</head>

<body>
    <?php include BASE_PATH . 'views/components/header.component.php'; ?>

    <div class="container">
        <h1>Jugadores</h1>
        <div class="players-grid" id="playersContainer"></div>
        <a href="<?php echo BASE_URL; ?>teams" class="back-button">Volver a equipos</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadPlayers('<?php echo BASE_URL; ?>', <?php echo $teamId; ?>);
        });
    </script>

    <?php include BASE_PATH . 'views/components/footer.component.php'; ?>
</body>

</html>