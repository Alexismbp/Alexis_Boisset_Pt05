<!-- Alexis Boisset -->
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro furbo</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/main/styles.css">
    <script src="<?php echo BASE_URL; ?>scripts/index.js" defer></script>
</head>

<body>
    <?php include BASE_PATH . 'views/components/header.component.php'; ?>

    <div class="main-content-wrapper">
        <?php include BASE_PATH . 'views/layouts/feedback.view.php'; ?>

        <h1> Gestor de Partits </h1>

        <?php include BASE_PATH . 'views/components/league-selector.component.php'; ?>
        <?php include BASE_PATH . 'views/components/matches-per-page.component.php'; ?>

        <h2> Llista de partits </h2>

        <?php include BASE_PATH . 'views/components/matches-list.component.php'; ?>
        <?php include BASE_PATH . 'views/components/pagination.component.php'; ?>

        <h1>Lista de Artículos Compartidos</h1>
        <button id="update-shared-articles">Actualizar Lista de Artículos Compartidos</button>
        <div id="shared-articles">
            <!-- Artículos compartidos se cargarán aquí -->
        </div>
        <div class="shared-articles-container">
            <?php foreach ($sharedArticles as $row): ?>
                <div class="shared-article-card">
                    <h3><?= htmlspecialchars($row['article_title']); ?></h3>
                    <p><strong>Partido:</strong> <?= htmlspecialchars($row['equipo_local']) . " vs " . htmlspecialchars($row['equipo_visitante']); ?></p>
                    <p><strong>Data partit:</strong> <?= htmlspecialchars($row['data']); ?></p>
                    <p><strong>Mostrar Título:</strong> <?= $row['show_title'] ? 'Sí' : 'No'; ?></p>
                    <p><strong>Mostrar Contenido:</strong> <?= $row['show_content'] ? 'Sí' : 'No'; ?></p>
                    <p><strong>Creado:</strong> <?= $row['created_at']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include BASE_PATH . 'views/components/footer.component.php'; ?>

    <script>
        document.getElementById('update-shared-articles').addEventListener('click', function() {
            fetch('<?php echo BASE_URL; ?>shared-articles')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    const articlesContainer = document.getElementById('shared-articles');
                    articlesContainer.innerHTML = '';
                    data.forEach(article => {
                        const articleElement = document.createElement('div');
                        articleElement.classList.add('shared-article-card');
                        articleElement.innerHTML = `
                        <strong>${article.article_title}</strong>
                        <p>${article.content}</p>
                        <small>${article.created_at}</small>
                    `;
                        articlesContainer.appendChild(articleElement);
                    });
                })
                .catch(error => {
                    console.error('Error al cargar los artículos compartidos:', error);
                    const articlesContainer = document.getElementById('shared-articles');
                    articlesContainer.innerHTML = `<p class="error">Error: ${error.message}</p>`;
                });
        });
    </script>
</body>

</html>