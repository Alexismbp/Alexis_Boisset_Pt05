<!-- Alexis Boisset -->
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de partits</title>
    <link rel="stylesheet" href="<?php echo BASE_URL ?>views/main/styles.css">
</head>

<header>
    <?php if (!isset($_SESSION['loggedin'])): ?>
        <!-- Opciones de logarse o registrar-se cuando no está logado -->
        <a href="/login" class="btn-login">Logar-se</a>
        <a href="/register" class="btn-register">Enregistrar-se</a>
    <?php else: ?>
        <!-- Mensaje cuando el usuario ya está logado -->
        <p>Benvingut, <?php echo $_SESSION['username']; ?>!</p>
        <a href="/logout" class="btn-logout">Tancar sessió</a>
    <?php endif; ?>
</header>

<body>
    <?php
    // FEEDBACK
    include BASE_PATH . 'views/layouts/feedback.view.php';
    ?>
    <h1>Gestor de Partits</h1>

    <!-- Enllaços per a gestionar els partits (només loguejat) -->
    <?php if ($_SESSION['loggedin']) : ?>
        <ul>
            <li><a href="/create">Crear nou partit</a></li>
            <li><a href="/delete">Eliminar un partit</a></li>
        </ul>
    <?php endif ?>

    <!-- Select per a triar la lliga -->
    <?php if ($_SESSION['loggedin'] == false) : ?>
        <!-- Mostrar select de lliga NOMÉS SI NO ESTA LOGUEJAT -->
        <form method="GET" action="index.php" class="form-lliga">
            <label for="lliga">Selecciona la lliga:</label>
            <select id="lliga" name="lliga" onchange="this.form.submit()">
                <option value="LaLiga" <?php if ($lligaSeleccionada == 'LaLiga') echo 'selected'; ?>>LaLiga</option>
                <option value="Premier League" <?php if ($lligaSeleccionada == 'Premier League') echo 'selected'; ?>>Premier League</option>
                <option value="Ligue 1" <?php if ($lligaSeleccionada == 'Ligue 1') echo 'selected'; ?>>Ligue 1</option>
            </select>
        </form>
        <?php else:

        #Es fa us de switch per a que una vegada logat no pugui canviar de lliga, només tindrà una opció
        switch ($lligaSeleccionada):
            case 'LaLiga': ?>
                <label for="lliga">Lliga seleccionada:</label>
                <select id="lliga" name="lliga" onchange="this.form.submit()">
                    <option value="LaLiga" <?php if ($lligaSeleccionada == 'LaLiga') echo 'selected'; ?>>LaLiga</option>
                </select>

            <?php break;

            case 'Premier League': ?>
                <label for="lliga">Lliga seleccionada:</label>
                <select id="lliga" name="lliga" onchange="this.form.submit()">
                    <option value="Premier League" <?php if ($lligaSeleccionada == 'Premier League') echo 'selected'; ?>>Premier League</option>
                </select>

            <?php break;

            case 'Ligue 1': ?>
                <label for="lliga">Lliga seleccionada:</label>
                <select id="lliga" name="lliga" onchange="this.form.submit()">
                    <option value="Ligue 1" <?php if ($lligaSeleccionada == 'Ligue 1') echo 'selected'; ?>>Ligue 1</option>
                </select>

    <?php break;
        endswitch;
    endif; ?>


    <!-- Select per a triar quants partits mostrar per pàgina -->
    <form method="GET" action="index.php" class="form-partits-per-page">
        <label for="partitsPerPage">Partits per pàgina:</label>
        <select id="partitsPerPage" name="partitsPerPage" onchange="this.form.submit()">
            <option value="5" <?php if ($partitsPerPage == 5) echo 'selected'; ?>>5</option>
            <option value="10" <?php if ($partitsPerPage == 10) echo 'selected'; ?>>10</option>
            <option value="15" <?php if ($partitsPerPage == 15) echo 'selected'; ?>>15</option>
            <option value="20" <?php if ($partitsPerPage == 20) echo 'selected'; ?>>20</option>
        </select>
    </form>

    <h2>Llista de partits</h2>
    <?php if (count($partits) > 0): ?>
        <div class="partits">
            <?php foreach ($partits as $partit): ?>
                <div class="partit">
                    <h3><?php echo htmlspecialchars($partit['equip_local']) . " vs " . htmlspecialchars($partit['equip_visitant']); ?></h3>

                    <?php if ($partit['jugat']): ?>
                        <!-- Si el partit ja s'ha jugat, mostrar el resultat -->
                        <p>Resultat: <?php echo $partit['gols_local'] . " - " . $partit['gols_visitant']; ?></p>
                    <?php else: ?>
                        <!-- Si el partit encara no s'ha jugat, mostrar la data programada -->
                        <p>Partit programat per al: <?php echo date('d-m-Y', strtotime($partit['data'])); ?></p>
                    <?php endif; ?>


                    <!-- WORK IN PROGRESS, no afecta al funcionament del programa -->
                    <?php if (!$partit['jugat'] && $_SESSION['loggedin'] == true): ?>
                        <span><b>Work in progress, better not to touch (risk of crash)</b></span>
                        <form action="controllers/guardar_prediccio.php" method="POST">
                            <input type="hidden" name="partit_id" value="<?php echo $partit['id']; ?>">
                            <label for="gols_local">Gols Local:</label>
                            <input type="number" name="gols_local" min="0" required>
                            <label for="gols_visitant">Gols Visitant:</label>
                            <input type="number" name="gols_visitant" min="0" required>
                            <button type="submit">Guardar Predicció</button>
                        </form>
                        <br>
                    <?php endif; ?>

                    <!-- Si esta loguejat mostra botons de edit y delete -->
                    <?php if ($_SESSION['loggedin']): ?>
                        <a href="<?php echo BASE_URL?>controllers/crud/save-match.controller.php?id=<?php echo $partit['id'] ?>">Editar Partit</a>
                        <a href="<?php echo BASE_URL?>views/crud/delete/delete.view.php?id=<?php echo $partit['id'] ?>">Eliminar Partit</a>
                    <?php endif; ?>
                    <span>ID de partit: <?php echo $partit['id'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        </div>


        <!-- Navegació de paginació -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&partitsPerPage=<?= $partitsPerPage ?>&lliga=<?= $lligaSeleccionada ?>">Anterior</a>
            <?php endif; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>&partitsPerPage=<?= $partitsPerPage ?>&lliga=<?= $lligaSeleccionada ?>">Següent</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p class="partit">No hi ha partits disponibles.</p>
    <?php endif; ?>
</body>

</html>