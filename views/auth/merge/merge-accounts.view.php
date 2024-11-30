
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fusionar Comptes</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/auth/merge/styles_merge.css">
</head>
<body>
    <div class="container">
        <h1>Compte Existent</h1>
        <p>El correu <?php echo htmlspecialchars($_SESSION['temp_email']); ?> ja està associat a un compte.</p>
        <p>Vols fusionar els comptes per poder accedir amb els dos mètodes?</p>
        
        <form action="<?php echo BASE_URL; ?>merge-accounts" method="POST">
            <input type="hidden" name="provider" value="<?php echo htmlspecialchars($_SESSION['temp_provider']); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['temp_email']); ?>">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($_SESSION['temp_name']); ?>">
            
            <button type="submit" name="action" value="merge" class="btn-merge">Sí, fusionar comptes</button>
            <button type="submit" name="action" value="cancel" class="btn-cancel">No, cancel·lar</button>
        </form>
    </div>
</body>
</html>