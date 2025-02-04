<?php
$is_edit = (isset($_GET['action']) && $_GET['action'] === 'edit');
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Article Compartit<?= $is_edit ? ' - Editar' : ''; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/shared/styles_shared-article.css">
</head>

<body>
    <?php if ($is_edit): ?>
        <h1>Editar Article Compartit</h1>
        <form action="<?php echo BASE_URL; ?>share-article" method="POST">
            <input type="hidden" name="match_id" value="<?php echo $shared['match_id']; ?>">
            <label for="title">Títol</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($shared['title'] ?? ''); ?>" required>
            <br>
            <label for="content">Contingut</label>
            <textarea id="content" name="content" rows="10" required><?= htmlspecialchars($shared['content'] ?? ''); ?></textarea>
            <br>
            <div class="button-group">
                <button type="submit" class="btn-submit">Donar d'alta</button>
                <a href="<?php echo BASE_URL; ?>shared-articles" class="btn-back">Cancelar</a>
            </div>
        </form>
    <?php else: ?>
        <h1>Article Compartit</h1>

        <?php if (isset($shared)): ?>
            <button id="edit-article-btn" type="button">Editar</button>
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
            <button id="share-article-btn">Compartir Article</button>

            <div id="share-result" style="display:none; margin-top:20px;">
                <h3>QR Code:</h3>
                <img id="qr-code" src="" alt="QR Code">
                <p>URL per compartir: <a id="share-url" href="#" target="_blank"></a></p>
            </div>
        <?php else: ?>
            <p>No s'ha trobat cap article per mostrar.</p>
        <?php endif; ?>
    <?php endif; ?>

    <script>
        const BASE_URL = "<?= BASE_URL; ?>";

        document.getElementById("edit-article-btn").addEventListener("click", function() {
            window.location.href = window.location.href + "?action=edit";
        });
    </script>
    <?php if (!$is_edit): ?>
        <script src="<?= BASE_URL; ?>views/shared/share_article.js"></script>
    <?php endif; ?>
</body>

</html>