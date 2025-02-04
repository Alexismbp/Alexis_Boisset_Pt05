<?php

use chillerlan\QRCode\QRCode;
// Iniciar el almacenamiento en buffer y definir cabeceras JSON
ob_start();
header('Content-Type: application/json');

// Ajusta la ruta al autoload según tu estructura de directorios
require_once __DIR__ . '/../../vendor/autoload.php';


// Solo se permite el método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}

// Verificar que se haya recibido el archivo
if (!isset($_FILES['qrimage'])) {
    echo json_encode(['success' => false, 'error' => 'No se ha enviado ningún archivo.']);
    exit;
}

if ($_FILES['qrimage']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Error al subir el archivo.']);
    exit;
}

$tmpPath = $_FILES['qrimage']['tmp_name'];

try {
    // Instanciar la clase QRCode y leer el QR de la imagen
    $qr = new QRCode();
    $result = $qr->readFromFile($tmpPath);

    // Devuelve el contenido del QR (puede ser una URL o cualquier texto)
    echo json_encode([
        'success' => true,
        'data'    => $result->data
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
