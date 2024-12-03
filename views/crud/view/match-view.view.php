<?php
require_once __DIR__ . "/../../../models/env.php";
require_once BASE_PATH . 'controllers/utils/SessionHelper.php';
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Detalls del Partit</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/crud/view/styles_match-view.css">
</head>
<body>
    <header>
        <?php include BASE_PATH . 'views/components/header.component.php'; ?>
    </header>

    <div class="match-details">
        <h1><?php echo htmlspecialchars($_SESSION['equip_local'] ?? '', ENT_QUOTES, 'UTF-8'); ?> vs <?php echo htmlspecialchars($_SESSION['equip_visitant'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>
        <p>Data: <?php echo htmlspecialchars($_SESSION['data'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Gols Local: <?php echo htmlspecialchars($_SESSION['gols_local'] ?? '0', ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Gols Visitant: <?php echo htmlspecialchars($_SESSION['gols_visitant'] ?? '0', ENT_QUOTES, 'UTF-8'); ?></p>

        <?php if (!empty($_SESSION['article_title'] ?? '') && !empty($_SESSION['article_content'] ?? '')): ?>
            <div class="article">
                <h2><?php echo htmlspecialchars($_SESSION['article_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($_SESSION['article_content'] ?? '', ENT_QUOTES, 'UTF-8')); ?></p>
            </div>
        <?php endif; ?>

        <div class="btn-actions">
            <?php if (isset($_SESSION['loggedin'])): ?>
                <?php 
                $match_id = isset($router) ? $router->getParam('id') : '';
                if ($match_id): 
                ?>
                    <a href="<?php echo BASE_URL; ?>/edit-match/<?php echo isset($match_id) ? htmlspecialchars($match_id, ENT_QUOTES, 'UTF-8') : ""; ?>">Editar Partit</a>
                <?php endif; ?>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>" class="btn-back-main">Tornar enrere</a>
        </div>
    </div>

    <footer>
        <!-- ...existing footer content... -->
    </footer>
</body>
</html>