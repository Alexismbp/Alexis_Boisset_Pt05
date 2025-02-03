<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Article Compartit</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/shared/styles_shared-article.css">
</head>

<body>
    <h1>Article Compartit</h1>

    <?php if (isset($shared)): ?>
        <?php if ($shared['title']): ?>
            <h2><?= htmlspecialchars($shared['title']); ?></h2>
        <?php else: ?>
            <h2>Sense títol</h2>
        <?php endif; ?>

        <?php if ($shared['content']): ?>
            <p><?= nl2br(htmlspecialchars($shared['content'])); ?></p>
        <?php else: ?>
            <p>Sense contingut</p>
        <?php endif; ?>

        <label>
            <input type="checkbox" id="show-title" checked> Mostrar Títol
        </label>
        <br>
        <label>
            <input type="checkbox" id="show-content" checked> Mostrar Contingut
        </label>
        <br><br>
        <button id="share-article-btn" data-article-id="<?= $shared['article_id']; ?>" data-match-id="<?= $shared['match_id']; ?>">Compartir Article</button>

        <div id="share-result" style="display:none; margin-top:20px;">
            <h3>QR Code:</h3>
            <img id="qr-code" src="" alt="QR Code">
            <p>URL per compartir: <a id="share-url" href="#" target="_blank"></a></p>
        </div>
    <?php else: ?>
        <p>No s'ha trobat cap article per mostrar.</p>
    <?php endif; ?>

    <script>
        const BASE_URL = "<?= BASE_URL; ?>";
    </script>
    <script src="<?= BASE_URL; ?>views/shared/share_article.js"></script>
</body>

</html>