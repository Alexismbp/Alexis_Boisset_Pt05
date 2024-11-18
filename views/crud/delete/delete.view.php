<!-- Alexis Boisset -->
<?php
require "../../private/controller/session.controller.php"; // Detecció de temps d'inactivitat
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ./login.view.php");
}
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
        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>'; // Missatge d'èxit.
            unset($_SESSION['success']);
        } elseif (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>'; // Missatge d'error.
            unset($_SESSION['error']);
        }
        ?>

        <!-- Formulari -->
        <form id="deleteForm" action="../../private/controller/delete.php" method="post">
            <label for="id">ID del partit a eliminar (numèrica):</label>
            <input type="text" class="form-control" id="id" name="partit_id" placeholder="Escriu l'ID del partit" value="<?php echo $_GET['id'] ?>" required>

            <div class="d-grid gap-2 mt-4">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                    Eliminar
                </button>
            </div>
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