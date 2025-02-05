<?php
// views/qr/qr-read.view.php
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Lector de QR</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/main/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/qr/styles_qr.css">
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>scripts/qr-reader.js" defer></script>
</head>

<body>
    <div class="page-container">
        <?php include BASE_PATH . 'views/components/header.component.php'; ?>

        <main class="content-container">
            <div class="qr-reader-container">
                <h1>Lector de QR</h1>
                <form id="qr-form" class="qr-form" enctype="multipart/form-data">
                    <label for="qr-image">Selecciona una imatge del QR:</label>
                    <input type="file" id="qr-image" name="qrimage" accept="image/*" required>
                    <button type="submit" class="btn-submit">Escanear QR</button>
                </form>
                <div class="result-container">
                    <label for="qr-output">Resultat:</label>
                    <input type="text" id="qr-output" readonly>
                    <button id="go-to-link" class="btn-link">Anar a l'enllaç</button>
                </div>
            </div>
        </main>

        <?php include BASE_PATH . 'views/components/footer.component.php'; ?>
    </div>
</body>

</html>