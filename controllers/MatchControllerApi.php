<?php

class MatchControllerApi
{
    // Almacenamos la conexión a la base de datos en lugar de un modelo
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // GET /api/partidos - Listar todos
    public function apiGetPartidos()
    {
        // Se reemplaza $this->model->getAllMatches() por la función consultarPartits()
        $partidos = consultarPartits($this->conn);
        $this->jsonResponse(['data' => $partidos]);
    }

    // GET /api/partidos/{id} - Obtener uno
    public function apiGetPartido($id)
    {
        // Se reemplaza $this->model->getMatchById($id) por llamar a consultarPartido()
        $partido = consultarPartido($this->conn, $id);
        $this->jsonResponse(['data' => $partido]);
    }

    // POST /api/partidos - Crear
    public function apiCreatePartido()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $this->validateMatchData($data);
            // Se cambia la creación del partido por la función crearPartit()
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
            // Se actualiza el partido con la función actualitzarPartit()
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
        $required = ['equipo_local', 'equipo_visitante', 'fecha'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->jsonResponse(['error' => "Campo $field requerido"], 400);
            }
        }
    }
}
