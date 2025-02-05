<?php
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/utils/article.model.php';

$conn = Database::getInstance();
$sharedArticles = getAllSharedArticles($conn);

header('Content-Type: application/json');
echo json_encode($sharedArticles);
exit;
