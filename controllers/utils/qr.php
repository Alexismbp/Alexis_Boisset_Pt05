<?php
require_once __DIR__ . "/../../models/env.php";
require_once __DIR__ . "/../../models/database/database.model.php";
require_once __DIR__ . '/../../vendor/autoload.php';

use chillerlan\QRCode\{QRCode, QROptions};

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = Database::getInstance();
        
        // Validación de datos
        if (!isset($_POST['article_id'], $_POST['match_id'])) {
            throw new Exception('Faltan parámetros requeridos');
        }

        $article_id = filter_var($_POST['article_id'], FILTER_VALIDATE_INT);
        $match_id = filter_var($_POST['match_id'], FILTER_VALIDATE_INT);
        
        if ($article_id === false || $match_id === false) {
            throw new Exception('IDs inválidos');
        }

        $show_title = isset($_POST['titol']) ? 1 : 0;
        $show_content = isset($_POST['cos']) ? 1 : 0;
        
        // Generar token y URL relativa
        $token = bin2hex(random_bytes(16));
        $url = 'share/' . $token; // URL relativa sin BASE_URL

        // Preparar y ejecutar la inserción
        $stmt = $pdo->prepare("INSERT INTO shared_articles (token, article_id, match_id, show_title, show_content) VALUES (?, ?, ?, ?, ?)");
        
        // Ejecutar la consulta
        if (!$stmt->execute([$token, $article_id, $match_id, $show_title, $show_content])) {
            throw new Exception('Error al guardar en la base de datos');
        }

        // Configurar opciones del QR
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel' => QRCode::ECC_L,
            'version' => 5,
        ]);

        // Generar código QR
        $qrcode = new QRCode($options);
        $qrSvg = $qrcode->render($url);

        // Devolver respuesta exitosa
        echo json_encode([
            'success' => true,
            'url' => $url,
            'qr' => $qrSvg
        ]);
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido'
    ]);
}
?>