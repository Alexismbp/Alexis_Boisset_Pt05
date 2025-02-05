<!-- Alexis Boisset -->
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro furbo</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/main/styles.css">
    <script src="<?php echo BASE_URL; ?>/scripts/index.js" defer></script>
</head>

<body>
    <?php include BASE_PATH . 'views/components/header.component.php'; ?>

    <div class="main-content-wrapper">
        <?php include BASE_PATH . 'views/layouts/feedback.view.php'; ?>

        <h1>Lista de Artículos Compartidos</h1>
        <button id="update-shared-articles">Actualizar Lista de Artículos Compartidos</button>
        <a href="<?= BASE_URL ?>qr-read">Llegir QR</a>
        <div class="shared-articles-container">
            <?php foreach ($sharedArticles as $row): ?>
                <div class="shared-article-card">
                    <h3><?= htmlspecialchars($row['article_title']); ?></h3>
                    <p><strong>Partido:</strong> <?= htmlspecialchars($row['equipo_local']) . " vs " . htmlspecialchars($row['equipo_visitante']); ?></p>
                    <p><strong>Data partit:</strong> <?= htmlspecialchars($row['data']); ?></p>
                    <p><strong>Mostrar Título:</strong> <?= $row['show_title'] ? 'Sí' : 'No'; ?></p>
                    <p><strong>Mostrar Contenido:</strong> <?= $row['show_content'] ? 'Sí' : 'No'; ?></p>
                    <p><small>Creado: <?= $row['created_at']; ?></small></p>
                    <a href="<?php echo rtrim(BASE_URL, '/') . '/shared/' . htmlspecialchars($row['token']); ?>?action=edit" class="btn">Dar de alta</a>
                    <!-- Nuevo botón para mostrar QR -->
                    <button class="btn-show-qr" data-token="<?= htmlspecialchars($row['token']); ?>">Mostrar QR</button>
                    <!-- Contenedor oculto para el QR -->
                    <div class="qr-container" style="display:none; margin-top:10px;">
                        <img src="" alt="QR Code" />
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include BASE_PATH . 'views/components/footer.component.php'; ?>

    <script>
        const baseUrl = "<?php echo BASE_URL; ?>";
    </script>
    <script src="<?php echo BASE_URL; ?>scripts/shared-articles.js"></script>
</body>

</html>