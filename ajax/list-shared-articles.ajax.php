<?php
// Habilitar reporte de errores solo en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/utils/article.model.php';

$conn = Database::getInstance();
$sharedArticles = getAllSharedArticles($conn);

// Registrar en el log para depuración
error_log(print_r($sharedArticles, true));

header('Content-Type: application/json');
echo json_encode($sharedArticles);
exit;
