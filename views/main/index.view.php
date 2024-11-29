<!-- Alexis Boisset -->
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de partits</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/main/styles.css">
    <script src="<?php echo BASE_URL; ?>scripts/index.js" defer></script>
</head>

<body>
    <?php include BASE_PATH . 'views/components/header.component.php'; ?>
    <?php include BASE_PATH . 'views/layouts/feedback.view.php'; ?>

    <h1> Gestor de Partits </h1>

    <?php include BASE_PATH . 'views/components/league-selector.component.php'; ?>
    <?php include BASE_PATH . 'views/components/matches-per-page.component.php'; ?>

    <h2> Llista de partits </h2>

    <?php include BASE_PATH . 'views/components/matches-list.component.php'; ?>
    <?php include BASE_PATH . 'views/components/pagination.component.php'; ?>
    <?php include BASE_PATH . 'views/components/footer.component.php'; ?>
</body>

</html>