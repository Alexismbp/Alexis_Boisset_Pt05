<?php
// Alexis Boisset
// Vista per veure els detalls d'un partit per cerca a la barra 
// de busqueda o per clic a enllaç "veure" que hi ha a cada partit.

require_once __DIR__ . "/../../../models/env.php";
require_once BASE_PATH . 'controllers/utils/SessionHelper.php';
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalls del Partit</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/crud/view/styles_match-view.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/main/styles.css">
</head>
<body>
    <?php include BASE_PATH . 'views/components/header.component.php'; ?>

    <div class="match-details">
        <h1><?php echo htmlspecialchars($partit['equip_local'], ENT_QUOTES, 'UTF-8'); ?> vs <?php echo htmlspecialchars($partit['equip_visitant'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <p>Data: <?php echo htmlspecialchars($partit['data'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Gols Local: <?php echo htmlspecialchars($partit['gols_local'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Gols Visitant: <?php echo htmlspecialchars($partit['gols_visitant'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Jugat: <?php echo $partit['jugat'] ? 'Sí' : 'No'; ?></p>

        <?php if ($article): ?>
            <div class="article">
                <h2><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($article['content'], ENT_QUOTES, 'UTF-8')); ?></p>
                <p>Publicado por: <?php echo htmlspecialchars(getUsernameById($conn, $article['user_id']), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar</a>
    </div>

   
    <script src="<?php echo BASE_URL; ?>scripts/search.js" defer></script>
</body>
</html>