<?php
// Alexis Boisset

require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/utils/article.model.php';

$conn = Database::getInstance();
$sharedArticles = getAllSharedArticles($conn);

include BASE_PATH . 'views/shared/list-shared-articles.view.php';
