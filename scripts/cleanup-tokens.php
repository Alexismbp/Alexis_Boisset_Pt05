
<?php
require_once __DIR__ . '/../models/database/database.model.php';
require_once __DIR__ . '/../models/user/user.model.php';

try {
    $conn = Database::getInstance();
    cleanupExpiredTokens($conn);
    echo "Limpieza de tokens completada: " . date('Y-m-d H:i:s') . "\n";
} catch (Exception $e) {
    error_log("Error en la limpieza de tokens: " . $e->getMessage());
    echo "Error en la limpieza de tokens\n";
}