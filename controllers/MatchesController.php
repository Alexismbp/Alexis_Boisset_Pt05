<?php
class MatchesController {
    private $dbService;

    public function __construct($dbService) {
        $this->dbService = $dbService;
    }

    public function getMatches($lliga, $page, $partitsPerPage) {
        $offset = ($page - 1) * $partitsPerPage;
        $sql = "SELECT p.id, p.data, e_local.nom AS equip_local, 
                       e_visitant.nom AS equip_visitant, p.gols_local, 
                       p.gols_visitant, p.jugat, l.nom AS lliga
                FROM partits p
                JOIN equips e_local ON p.equip_local_id = e_local.id
                JOIN equips e_visitant ON p.equip_visitant_id = e_visitant.id
                JOIN lligues l ON p.liga_id = l.id
                WHERE l.nom = :lliga
                LIMIT :limit OFFSET :offset";

        return $this->dbService->fetchAll($sql, [
            ':lliga' => $lliga,
            ':limit' => $partitsPerPage,
            ':offset' => $offset
        ]);
    }

    public function getTotalPages($lliga, $partitsPerPage) {
        $sql = "SELECT COUNT(*) FROM partits p
                JOIN lligues l ON p.liga_id = l.id
                WHERE l.nom = :lliga";
        
        $total = $this->dbService->fetchColumn($sql, [':lliga' => $lliga]);
        return ceil($total / $partitsPerPage);
    }

    public function getLligues() {
        $sql = "SELECT DISTINCT nom FROM lligues ORDER BY nom";
        return $this->dbService->fetchAll($sql);
    }
}