<?php
// Alexis Boisset
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Previsualització d'Article Compartit</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/shared/styles_shared-article.css">
</head>

<body>
    <div class="preview-container">
        <h1>Previsualització d'Article Compartit</h1>

        <?php if (isset($shared)): ?>
            <div class="article-preview">
                <?php if ($shared['show_title'] && $shared['title']): ?>
                    <div class="preview-section">
                        <h3>Títol:</h3>
                        <div class="preview-content">
                            <?= htmlspecialchars($shared['title']); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($shared['show_content'] && $shared['content']): ?>
                    <div class="preview-section">
                        <h3>Contingut:</h3>
                        <div class="preview-content">
                            <?= nl2br(htmlspecialchars($shared['content'])); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="action-buttons">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                    <form action="<?php echo BASE_URL; ?>create-match" method="GET">
                        <?php if ($shared['show_title']): ?>
                            <input type="hidden" name="shared_title" value="<?= htmlspecialchars($shared['title'] ?? ''); ?>">
                        <?php endif; ?>

                        <?php if ($shared['show_content']): ?>
                            <input type="hidden" name="shared_content" value="<?= htmlspecialchars($shared['content'] ?? ''); ?>">
                        <?php endif; ?>
                        <button type="submit" class="btn-primary">Crear Article al Meu Perfil</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>login" class="btn-primary">Inicia sessió per crear l'article</a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>" class="btn-secondary">Tornar a l'Inici</a>
            </div>
        <?php else: ?>
            <p class="error-message">No s'ha trobat cap article per mostrar.</p>
            <div class="action-buttons">
                <a href="<?php echo BASE_URL; ?>" class="btn-secondary">Tornar a l'Inici</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>