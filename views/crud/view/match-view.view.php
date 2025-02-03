<?php
// Alexis Boisset
// Vista per veure els detalls d'un partit per cerca a la barra 
// de busqueda o per clic a enllaç "veure" que hi ha a cada partit.

require_once __DIR__ . "/../../../models/env.php";
require_once BASE_PATH . 'controllers/utils/SessionHelper.php';
include_once BASE_PATH . 'views/components/modal-share.component.php';
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
        <div class="match-header">
            <div class="teams">
                <div class="team-header-l">
                    <?php echo htmlspecialchars($partit['equip_local'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div class="vs">vs</div>
                <div class="team-header-r">
                    <?php echo htmlspecialchars($partit['equip_visitant'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
            <p class="match-date">Data: <?php echo htmlspecialchars($partit['data'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <div class="score-container">
            <div class="team local">
                <span class="team-name"><?php echo htmlspecialchars($partit['equip_local'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="score"><?php echo htmlspecialchars($partit['gols_local'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="score-divider">-</div>
            <div class="team visitor">
                <span class="team-name"><?php echo htmlspecialchars($partit['equip_visitant'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="score"><?php echo htmlspecialchars($partit['gols_visitant'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        </div>

        <div class="match-status">
            <span class="status-badge <?php echo $partit['jugat'] ? 'played' : 'pending'; ?>">
                <?php echo $partit['jugat'] ? 'Partit Jugat' : 'Pendent'; ?>
            </span>
        </div>

        <?php if ($article): ?>
            <div class="article-container">
                <div class="article">
                    <h2><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <div class="article-content">
                        <p><?php echo nl2br(htmlspecialchars($article['content'], ENT_QUOTES, 'UTF-8')); ?></p>
                    </div>
                    <div class="article-footer">
                        <p class="author">Publicat per: <?php echo htmlspecialchars(getUsernameById($conn, $article['user_id']), ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="actions-container">
            <a href="<?php echo BASE_URL; ?>" class="btn-back">Tornar</a>
            <?php if ($article): ?>
                <button class="btn-share" data-modal-target=".modal-content">Compartir</button>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>scripts/search.js" defer></script>
</body>

</html>