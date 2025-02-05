<?php
// Prevenir cualquier salida antes del JSON
ob_start();

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2) . '/');
}

require_once BASE_PATH . "models/env.php";
require_once BASE_PATH . "models/database/database.model.php";
require_once BASE_PATH . 'vendor/autoload.php';
require_once BASE_PATH . "models/shared_article.model.php";

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;

// Limpiar cualquier salida anterior
ob_clean();

// Establecer headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sharedArticle = new SharedArticle();

        if (isset($_POST['token'])) {
            // Si se envía token, generar QR para la URL de compartir existente

            $token = htmlspecialchars(trim($_POST['token']), ENT_QUOTES, 'UTF-8');
            if (!$sharedArticle->validateToken($token)) {
                throw new Exception('Token inválido');
            }
            $url = rtrim(BASE_URL, '/') . '/shared/' . $token;
        } else {
            // Validación y creación de nuevo artículo compartido
            if (!isset($_POST['article_id'], $_POST['match_id'])) {
                throw new Exception('Faltan parámetros requeridos');
            }
            $article_id = filter_input(INPUT_POST, 'article_id', FILTER_VALIDATE_INT);
            $match_id = filter_input(INPUT_POST, 'match_id', FILTER_VALIDATE_INT);
            if ($article_id === false || $match_id === false) {
                throw new Exception('IDs inválidos');
            }

            // Verificar que al menos una checkbox está marcada
            $show_title = isset($_POST['titol']) ? 1 : 0;
            $show_content = isset($_POST['cos']) ? 1 : 0;

            if (!$show_title && !$show_content) {
                throw new Exception('Has de seleccionar almenys una opció per compartir');
            }

            // Generar token y URL relativa
            $token = bin2hex(random_bytes(16));
            $url = BASE_URL . 'share/' . $token;

            if (!$sharedArticle->createSharedArticle($token, $article_id, $match_id, $show_title, $show_content)) {
                throw new Exception('Error al guardar en la base de datos');
            }
        }

        // Configurar opciones y generar el código QR
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel' => EccLevel::L,
            'version' => 5,
            'addQuietzone' => true,
            'quietzoneSize' => 4,
        ]);
        $qr = new QRCode($options);
        $qrSvg = $qr->render($url);

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
