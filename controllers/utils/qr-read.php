<?php

/**
 * Script per llegir codis QR
 * 
 * Aquest fitxer proporciona la funcionalitat per llegir i processar imatges amb codis QR.
 * Utilitza la llibreria chillerlan\QRCode per descodificar els codis QR.
 * 
 * @author Alexis Boisset
 */

use chillerlan\QRCode\QRCode;

// Iniciem el buffer de sortida i configurem les capçaleres JSON
ob_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../vendor/autoload.php';

// Comprovem que el mètode sigui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Mètode no permès.']);
    exit;
}

// Verifiquem que s'ha rebut el fitxer
if (!isset($_FILES['qrimage'])) {
    echo json_encode(['success' => false, 'error' => 'No s\'ha enviat cap fitxer.']);
    exit;
}

// Comprovem que no hi ha hagut errors en la pujada
if ($_FILES['qrimage']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Error en pujar el fitxer.']);
    exit;
}

$tmpPath = $_FILES['qrimage']['tmp_name'];

try {
    // Instanciem la classe QRCode i llegim el QR de la imatge
    $qr = new QRCode();
    $result = $qr->readFromFile($tmpPath);

    // Retornem el contingut del QR (pot ser una URL o qualsevol text)
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
