<?php
require_once __DIR__ . "/../../models/env.php";
require_once BASE_PATH . 'models/database/database.model.php';
require_once BASE_PATH . 'models/utils/porra.model.php';
require_once BASE_PATH . '/controllers/utils/SessionHelper.php';

class SaveMatchController {
    private $conn;
    private $errors = [];
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = Database::getInstance();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handlePost();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            return $this->handleGet();
        }
        return $this->redirectWithError("Método no válido");
    }

    private function handlePost() {
        $data = $this->validateAndSanitizeInput();
        if (!empty($this->errors)) {
            return $this->redirectWithErrors($data);
        }

        if (empty($data['id'])) {
            // Crear nuevo partido
            try {
                $equip_local_id = $this->getTeamId($data['equip_local']);
                $equip_visitant_id = $this->getTeamId($data['equip_visitant']);
                $liga_id = getLigaID($this->conn, $equip_local_id);
                
                if (!$equip_local_id || !$equip_visitant_id) {
                    return $this->redirectWithError("Els equips seleccionats no són vàlids");
                }

                // Insertar partido y obtener su ID
                $partit_id = insertPartido(
                    $this->conn,
                    $equip_local_id,
                    $equip_visitant_id,
                    $liga_id,
                    $data['data'],
                    $data['gols_local'],
                    $data['gols_visitant']
                );

                // Manejar artículo si se proporcionan título y contenido
                if (!empty($data['article_title']) && !empty($data['article_content'])) {
                    insertArticle(
                        $this->conn,
                        $partit_id,
                        $data['article_title'],
                        $data['article_content'],
                        $data['user_id']
                    );
                }

                $_SESSION['success'] = "Partit creat correctament!";
                header("Location: " . BASE_URL);
                exit();
            } catch (PDOException $e) {
                return $this->redirectWithError("Error: " . $e->getMessage());
            }
        }

        try {
            // Obtenemos IDs de equipos
            $equip_local_id = $this->getTeamId($data['equip_local']);
            $equip_visitant_id = $this->getTeamId($data['equip_visitant']);
            
            if (!$equip_local_id || !$equip_visitant_id) {
                return $this->redirectWithError("Els equips seleccionats no són vàlids");
            }

            // Determinar si el partido está jugado
            $jugado = $this->isMatchPlayed($data['gols_local'], $data['gols_visitant']);

            // Actualizar partido
            $stmt = updatePartido(
                $this->conn, 
                $data['id'], 
                $equip_local_id, 
                $equip_visitant_id, 
                $data['data'], 
                $data['gols_local'], 
                $data['gols_visitant'],
                $jugado
            );

            if ($stmt->execute()) {
                // Manejar artículo si se proporcionan
                if (!empty($data['article_title']) && !empty($data['article_content'])) {
                    $existingArticle = getArticleByMatchId($this->conn, $data['id']);
                    if ($existingArticle) {
                        // Actualizar artículo existente
                        updateArticle(
                            $this->conn, 
                            $data['id'], 
                            $data['article_title'], 
                            $data['article_content'], 
                            $data['user_id']
                        );
                    } else {
                        // Insertar nuevo artículo
                        insertArticle(
                            $this->conn, 
                            $data['id'], 
                            $data['article_title'], 
                            $data['article_content'], 
                            $data['user_id']
                        );
                    }
                }

                $this->clearEditingSession();
                $_SESSION['success'] = "El partit s'ha actualitzat correctament!";
                header("Location: " . BASE_URL);
                exit();
            }
        } catch (PDOException $e) {
            return $this->redirectWithError("Error: " . $e->getMessage());
        }
    }

    private function handleGet() {
        $id = $_GET['id'];
        
        if (!is_numeric($id)) {
            return $this->redirectWithError('L\'ID ha de ser numèric');
        }

        try {
            // Consulta el partido para editar
            $stmt = consultarPartido($this->conn, $id);
            $partit = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$partit) {
                return $this->redirectWithError("Aquest partit no existeix");
            }

            // Obtener datos del artículo asociado
            $article = getArticleByMatchId($this->conn, $id);

            // Preparar datos para la vista
            $equip_local_name = getTeamName($this->conn, $partit['equip_local_id']);
            $equip_visitant_name = getTeamName($this->conn, $partit['equip_visitant_id']);

            SessionHelper::setSessionData([
                'equip_local' => $equip_local_name,
                'equip_visitant' => $equip_visitant_name,
                'data' => $partit['data'],
                'gols_local' => $partit['gols_local'],
                'gols_visitant' => $partit['gols_visitant'],
                'jugat' => $partit['jugat'],
                'id' => $id,
                'editant' => true,
                'lliga' => getLeagueNameByTeam($equip_local_name, $this->conn),
                'article_title' => $article['title'] ?? '',
                'article_content' => $article['content'] ?? ''
            ]);

            header("Location: " . BASE_URL . "views/crud/edit/match-edit.view.php");
            exit();

        } catch (PDOException $e) {
            return $this->redirectWithError("Error: " . $e->getMessage());
        }
    }

    private function validateAndSanitizeInput() {
        $data = [
            'id' => filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT),
            'equip_local' => filter_input(INPUT_POST, 'equip_local', FILTER_SANITIZE_SPECIAL_CHARS),
            'equip_visitant' => filter_input(INPUT_POST, 'equip_visitant', FILTER_SANITIZE_SPECIAL_CHARS),
            'data' => filter_input(INPUT_POST, 'data', FILTER_SANITIZE_SPECIAL_CHARS),
            'gols_local' => $_POST['gols_local'] === "" ? null : filter_input(INPUT_POST, 'gols_local', FILTER_SANITIZE_NUMBER_INT),
            'gols_visitant' => $_POST['gols_visitant'] === "" ? null : filter_input(INPUT_POST, 'gols_visitant', FILTER_SANITIZE_NUMBER_INT),
            'article_title' => filter_input(INPUT_POST, 'article_title', FILTER_SANITIZE_SPECIAL_CHARS),
            'article_content' => filter_input(INPUT_POST, 'article_content', FILTER_SANITIZE_SPECIAL_CHARS),
            'user_id' => $_SESSION['userid'] // Asumiendo que el ID del usuario está en la sesión
        ];

        $this->validateInput($data);
        return $data;
    }

    private function validateInput($data) {
        if (empty($data['equip_local'])) {
            $this->errors[] = 'L\'equip local no pot estar buit';
        }
        if (empty($data['equip_visitant'])) {
            $this->errors[] = 'L\'equip visitant no pot estar buit';
        }
        if (empty($data['data'])) {
            $this->errors[] = 'La data no pot estar buida';
        }
        if (!empty($data['id']) && !is_numeric($data['id'])) {
            $this->errors[] = 'L\'ID ha de ser numèric';
        }
        // Opcional: Validar título y contenido del artículo si se proporcionan
        if (!empty($data['article_title']) && empty($data['article_content'])) {
            $this->errors[] = 'El contingut de l\'article no pot estar buit si es proporciona el títol.';
        }
        if (!empty($data['article_content']) && empty($data['article_title'])) {
            $this->errors[] = 'El títol de l\'article no pot estar buit si es proporciona el contingut.';
        }
    }

    private function isMatchPlayed($gols_local, $gols_visitant) {
        return (!is_null($gols_local) && !is_null($gols_visitant)) ? 1 : 0;
    }

    private function getTeamId($teamName) {
        try {
            return getTeamID($this->conn, $teamName);
        } catch (PDOException $e) {
            $this->errors[] = "Error al obtener el ID del equipo: " . $e->getMessage();
            return false;
        }
    }

    private function clearEditingSession() {
        unset($_SESSION['id'], $_SESSION['editant']);
    }

    private function redirectWithErrors($data) {
        SessionHelper::setSessionData([
            'equip_local' => $data['equip_local'],
            'equip_visitant' => $data['equip_visitant'],
            'data' => $data['data'],
            'gols_local' => $data['gols_local'],
            'gols_visitant' => $data['gols_visitant'],
            'errors' => $this->errors
        ]);
        header("Location: " . BASE_URL . "views/crud/edit/match-edit.view.php");
        exit();
    }

    private function redirectWithError($message) {
        $_SESSION['failure'] = $message;
        header("Location: " . BASE_URL . "views/crud/edit/match-edit.view.php");
        exit();
    }
}

// Iniciar el controlador
$controller = new SaveMatchController();
$controller->handleRequest();
