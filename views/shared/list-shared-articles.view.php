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
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include BASE_PATH . 'views/components/footer.component.php'; ?>

    <script>
        const articlesContainer = document.querySelector('.shared-articles-container');
        articlesContainer.style.transition = 'opacity 0.3s ease';
        document.getElementById('update-shared-articles').addEventListener('click', function() {
            articlesContainer.style.opacity = 0;
            fetch('<?php echo BASE_URL; ?>ajax-shared-articles')
                .then(response => response.text())
                .then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('Error parseando JSON: ' + e.message);
                    }
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    let inner = '';
                    data.forEach(article => {
                        inner += `
                        <div class="shared-article-card">
                            <h3>${article.article_title}</h3>
                            <p><strong>Partido:</strong> ${article.equipo_local} vs ${article.equipo_visitante}</p>
                            <p><strong>Data partit:</strong> ${article.data}</p>
                            <p><strong>Mostrar Título:</strong> ${article.show_title ? 'Sí' : 'No'}</p>
                            <p><strong>Mostrar Contenido:</strong> ${article.show_content ? 'Sí' : 'No'}</p>
                            <p><small>Creado: ${article.created_at}</small></p>
                            <a href="<?php echo BASE_URL; ?>shared/${article.token}?action=edit" class="btn">Dar de alta</a>
                        </div>
                        `;
                    });
                    setTimeout(() => {
                        articlesContainer.innerHTML = inner;
                        articlesContainer.style.opacity = 1;
                    }, 300);
                })
                .catch(error => {
                    articlesContainer.innerHTML = `<p class="error">Error: ${error.message}</p>`;
                    articlesContainer.style.opacity = 1;
                });
        });
    </script>
</body>

</html>