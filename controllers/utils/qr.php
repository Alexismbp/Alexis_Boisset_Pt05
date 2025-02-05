<?php
// Alexis Boisset
/**
 * Controlador per generar codis QR per articles compartits
 * 
 * Aquest script gestiona la generació de codis QR per compartir articles.
 * Pot generar un nou enllaç compartit o recuperar-ne un d'existent mitjançant un token.
 */

// Prevenir qualsevol sortida abans del JSON
ob_start();

/**
 * Importació de dependències necessàries
 */
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

/**
 * Configuració inicial
 * - Neteja del buffer de sortida
 * - Configuració de les capçaleres HTTP
 * - Configuració d'errors
 */
// Netejar qualsevol sortida anterior
ob_clean();

// Establir capçaleres
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sharedArticle = new SharedArticle();

        /**
         * Procés amb token existent
         * Si es rep un token, es genera el QR per a l'URL compartida existent
         */
        if (isset($_POST['token'])) {
            // Si s'envia token, generar QR per a l'URL de compartir existent

            $token = htmlspecialchars(trim($_POST['token']), ENT_QUOTES, 'UTF-8');
            if (!$sharedArticle->validateToken($token)) {
                throw new Exception('Token invàlid');
            }
            $url = rtrim(BASE_URL, '/') . '/shared/' . $token;
        } else {
            /**
             * Procés de creació d'un nou article compartit
             * - Validació dels paràmetres rebuts
             * - Verificació de les opcions seleccionades
             * - Generació del token i URL
             */
            // Validació i creació de nou article compartit
            if (!isset($_POST['article_id'], $_POST['match_id'])) {
                throw new Exception('Falten paràmetres requerits');
            }
            $article_id = filter_input(INPUT_POST, 'article_id', FILTER_VALIDATE_INT);
            $match_id = filter_input(INPUT_POST, 'match_id', FILTER_VALIDATE_INT);
            if ($article_id === false || $match_id === false) {
                throw new Exception('IDs invàlids');
            }

            // Verificar que almenys una checkbox està marcada
            $show_title = isset($_POST['titol']) ? 1 : 0;
            $show_content = isset($_POST['cos']) ? 1 : 0;

            if (!$show_title && !$show_content) {
                throw new Exception('Has de seleccionar almenys una opció per compartir');
            }

            // Generar token i URL relativa
            $token = bin2hex(random_bytes(16));
            $url = BASE_URL . 'share/' . $token;

            if (!$sharedArticle->createSharedArticle($token, $article_id, $match_id, $show_title, $show_content)) {
                throw new Exception('Error al guardar a la base de dades');
            }
        }

        /**
         * Configuració i generació del codi QR
         * - Estableix les opcions del QR (tipus de sortida, nivell de correcció, etc.)
         * - Genera el codi QR en format SVG
         */
        // Configurar opcions i generar el codi QR
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel' => EccLevel::L,
            'version' => 5,
            'addQuietzone' => true,
            'quietzoneSize' => 4,
        ]);
        $qr = new QRCode($options);
        $qrSvg = $qr->render($url);

        /**
         * Retorna la resposta en format JSON amb:
         * - URL generada
         * - Codi QR en format SVG
         * - Indicador d'èxit
         */
        echo json_encode([
            'success' => true,
            'url' => $url,
            'qr' => $qrSvg
        ]);
    } catch (Exception $e) {
        /**
         * Gestió d'errors
         * - Registra l'error
         * - Retorna resposta d'error amb codi 400
         */
        error_log($e->getMessage());
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    /**
     * Gestió de mètodes HTTP no permesos
     * - Retorna error 405 si no és una petició POST
     */
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Mètode no permès'
    ]);
}
