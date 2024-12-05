<?php
// Alexis Boisset

require_once __DIR__ . '/../../models/env.php';
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/user/user.model.php';
require_once BASE_PATH . '/controllers/session/session.controller.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Si no està autenticat, pa casa. I si no es l'admin, també.
if (!isset($_SESSION['loggedin']) || $_SESSION['userid'] != 1) {
    header("Location: " . BASE_URL);
    exit();
}

$conn = Database::getInstance();
$users = getAllUsers($conn);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/admin/styles_manage-users.css">
</head>
<body>
    <div class="container">
        <h1>Gestionar Usuarios</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($user['nom_usuari'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($user['correu_electronic'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <form action="<?php echo BASE_URL; ?>delete-user" method="POST" onsubmit="return confirm('Segur que vols eliminar aquest usuari?');">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="btn-delete">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="<?php echo BASE_URL; ?>" class="btn-back">Volver</a>
    </div>
</body>
</html>