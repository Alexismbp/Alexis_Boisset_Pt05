<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/env.php';
require_once __DIR__ . '/../models/database/database.model.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = Database::getInstance();
        
        $article_id = filter_input(INPUT_POST, 'article_id', FILTER_VALIDATE_INT);
        $match_id = filter_input(INPUT_POST, 'match_id', FILTER_VALIDATE_INT);
        $titol = isset($_POST['titol']) ? 1 : 0;
        $cos = isset($_POST['cos']) ? 1 : 0;

        if (!$article_id || !$match_id) {
            throw new Exception('Invalid input data');
        }

        // Generar token único
        $token = bin2hex(random_bytes(16));

        // Insertar en la base de datos
        $stmt = $conn->prepare("INSERT INTO shared_articles (token, article_id, match_id, titol, cos) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$token, $article_id, $match_id, $titol, $cos]);

        // Generar URL para compartir
        $shareUrl = BASE_URL . "shared/" . $token;

        // Configurar opciones del QR
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => EccLevel::L,
            'scale' => 5,
            'imageBase64' => true,
            'version' => 5
        ]);

        // Generar QR
        $qrcode = new QRCode($options);
        $qrImage = $qrcode->render($shareUrl);

        echo json_encode([
            'success' => true,
            'qr' => $qrImage,
            'url' => $shareUrl
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed'
    ]);
}
