<?php
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
            $sql = "SELECT p.id, e_local.nom AS equip_local, e_visitant.nom AS equip_visitant, p.data
                    FROM partits p
                    JOIN equips e_local ON p.equip_local_id = e_local.id
                    JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
                    WHERE e_local.nom LIKE :term
                    OR e_visitant.nom LIKE :term
                    ORDER BY p.data DESC
                    LIMIT 5";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['term' => '%' . $term . '%']);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Registro de error para depuración
            error_log("Error en SearchController: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error en el servidor.']);
            exit;
        }
    }
}
?>