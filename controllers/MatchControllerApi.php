<?php
require_once BASE_PATH . '/models/utils/porra.model.php';

class MatchControllerApi
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // GET /api/partidos - Listar todos
    public function apiGetPartidos()
    {
        $partidos = getAllMatches($this->conn);
        $this->jsonResponse(['data' => $partidos]);
    }

    // GET /api/partidos/{id} - Obtener uno
    public function apiGetPartido($id)
    {
        $partido = consultarPartido($this->conn, $id);
        $this->jsonResponse(['data' => $partido]);
    }

    // POST /api/partidos - Crear
    public function apiCreatePartido()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $this->validateMatchData($data);

            crearPartit($this->conn, $data);
            $this->jsonResponse(['message' => 'Partido creado'], 201);
        }
    }

    // PUT /api/partidos/{id} - Actualizar
    public function apiUpdatePartido($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            $this->validateMatchData($data);

            actualitzarPartit($this->conn, $id, $data);
            $this->jsonResponse(['message' => 'Partido actualizado']);
        }
    }

    // DELETE /api/partidos/{id} - Eliminar
    public function apiDeletePartido($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Se elimina el partido con la función deletePartit()
            deletePartit($this->conn, $id);
            $this->jsonResponse(['message' => 'Partido eliminado']);
        }
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode(array_merge(['status' => 'success'], $data));
        exit;
    }

    private function validateMatchData($data)
    {
        $required = ['equipo_local', 'equipo_visitante', 'fecha', 'liga_id'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $this->jsonResponse(['error' => "Campo $field requerido"], 400);
            }
        }

        // Validar que liga_id sea un número positivo
        if (!is_numeric($data['liga_id']) || $data['liga_id'] <= 0) {
            $this->jsonResponse(['error' => "liga_id debe ser un número positivo"], 400);
        }
    }
}
