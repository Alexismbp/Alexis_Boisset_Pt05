<?php
// Alexis Boisset
require_once BASE_PATH . 'models/utils/porra.model.php';
class SearchController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function search($term) {
        if (empty($term)) {
            return [];
        }

        try {
            $results = searchBarQuery($this->conn, $term);
            return $results;
        } catch (PDOException $e) {
            error_log("Error en SearchController: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error en el servidor.']);
            exit;
        }
    }
}
?>