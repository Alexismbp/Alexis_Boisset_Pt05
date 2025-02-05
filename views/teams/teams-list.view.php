<?php
// Alexis Boisset
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equips</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/main/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/teams/styles_teams.css">
</head>

<body>
    <?php include BASE_PATH . 'views/components/header.component.php'; ?>

    <div class="container">
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <h1>Equips de <?= htmlspecialchars($_SESSION['lliga']) ?></h1>
        <?php else: ?>
            <h1>Equips de LaLiga</h1>
        <?php endif; ?>

        <?php include BASE_PATH . "views/layouts/feedback.view.php"; ?>

        <div class="teams-grid" id="teamsContainer">
            <?php if (isset($teams) && !isset($teams['error'])): ?>
                <?php if (isset($teams['response']) && is_array($teams['response'])): ?>
                    <?php foreach ($teams['response'] as $team): ?>
                        <div class="team-card">
                            <img src="<?= htmlspecialchars($team['team']['logo']) ?>"
                                alt="<?= htmlspecialchars($team['team']['name']) ?>"
                                class="team-logo">
                            <h2><?= htmlspecialchars($team['team']['name']) ?></h2>
                            <?php if (isset($team['venue']['name'])): ?>
                                <p><?= htmlspecialchars($team['venue']['name']) ?></p>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>team/<?= $team['team']['id'] ?>" class="view-players">
                                Veure Jugadors
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No s'han trobat equips per aquesta lliga</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Error: <?= htmlspecialchars($teams['error'] ?? 'Error desconegut') ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php include BASE_PATH . 'views/components/footer.component.php'; ?>
</body>

</html>