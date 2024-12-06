<?php
// Alexis Boisset
// Vista per eliminar un partit. Només es pot eliminar si l'usuari està autenticat.
require_once 'controllers/session/session.controller.php'; // Detecció de temps d'inactivitat

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si l'usuari no està autenticat, se va pa casa.
SessionHelper::checkLogin();
?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Partit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/styles_delete.css"> <!-- Fulla d'estils personalitzada -->
</head>

<body>
    <div class="container mt-5">
        <h1>Eliminar Partit</h1>

        <!-- Missatges de feedback -->
        
        <?php include BASE_PATH . "views/layouts/feedback.view.php";?>
        

        <!-- Formulari -->
        <form id="deleteForm" action="<?php echo BASE_URL; ?>delete-match" method="POST">
            <label for="partit_id">ID del partit a eliminar (numèrica):</label>
            <input type="hidden" name="partit_id" value="<?php echo $_GET['id'] ?>" required>
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>

        <!-- Modal de confirmació -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminació</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Estàs segur que vols eliminar aquest partit?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <!-- Enllaç per tornar enrere -->
        <a href="../index.php" class="btn btn-secondary">Tornar</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enviar el formulari quan es confirmi l'eliminació
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            document.getElementById('deleteForm').submit();
        });
    </script>
</body>

</html>