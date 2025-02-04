<?php
// views/qr/qr-read.view.php
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Lector de QR</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/main/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        #qr-output {
            width: 100%;
            max-width: 400px;
            padding: 8px;
            font-size: 1em;
        }

        .result-container {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }

        #go-to-link {
            padding: 8px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: none;
        }

        #go-to-link:hover {
            background-color: #218838;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("qr-form");
            const goToLinkBtn = document.getElementById("go-to-link");
            const qrOutput = document.getElementById("qr-output");

            form.addEventListener("submit", (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                fetch("<?php echo BASE_URL; ?>qr-read", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            qrOutput.value = data.data;
                            goToLinkBtn.style.display = 'block'; // Mostrar el botón
                        } else {
                            qrOutput.value = "Error: " + data.error;
                            goToLinkBtn.style.display = 'none'; // Ocultar el botón
                        }
                    })
                    .catch(error => {
                        qrOutput.value = "Error: " + error;
                        goToLinkBtn.style.display = 'none'; // Ocultar el botón
                    });
            });

            goToLinkBtn.addEventListener('click', () => {
                const url = qrOutput.value;
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>
</head>

<body>
    <h1>Lector de QR</h1>
    <form id="qr-form" enctype="multipart/form-data">
        <label for="qr-image">Selecciona una imatge del QR:</label>
        <input type="file" id="qr-image" name="qrimage" accept="image/*" required>
        <button type="submit">Escanear QR</button>
    </form>
    <div class="result-container">
        <label for="qr-output">Resultat:</label>
        <input type="text" id="qr-output" readonly>
        <button id="go-to-link">Anar a l'enllaç</button>
    </div>
</body>

</html>