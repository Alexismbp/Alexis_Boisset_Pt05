<?php
require_once BASE_PATH . '/models/utils/porra.model.php';

class MatchControllerApi
{
    // Constantes para códigos HTTP
    private const HTTP_OK = 200;
    private const HTTP_CREATED = 201;
    private const HTTP_BAD_REQUEST = 400;
    private const HTTP_NOT_FOUND = 404;
    private const HTTP_METHOD_NOT_ALLOWED = 405;
    private const HTTP_INTERNAL_ERROR = 500;

    // Se elimina o ignora la constante fija de API Key
    // private const API_KEY = 'MY_SECRET_API_KEY';

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // GET /api/partidos - Listar todos
    public function apiGetPartidos()
    {
        $this->checkApiKey();
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Mètode no permès', self::HTTP_METHOD_NOT_ALLOWED);
            }

            $partidos = getAllMatches($this->conn);
            if (empty($partidos)) {
                $this->jsonResponse(['message' => 'No hi ha partits disponibles'], self::HTTP_OK);
            }
            $this->jsonResponse(['data' => $partidos], self::HTTP_OK);
        } catch (PDOException $e) {
            error_log("Error a la base de dades: " . $e->getMessage());
            $this->jsonResponse(['error' => 'Error intern del servidor'], self::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
    }

    // GET /api/partidos/{id} - Obtener uno
    public function apiGetPartido($id)
    {
        $this->checkApiKey();
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Mètode no permès', self::HTTP_METHOD_NOT_ALLOWED);
            }

            if (!is_numeric($id) || $id <= 0) {
                throw new Exception('ID de partit invàlid', self::HTTP_BAD_REQUEST);
            }

            $partido = consultarPartido($this->conn, $id);
            if (!$partido) {
                throw new Exception('Partit no trobat', self::HTTP_NOT_FOUND);
            }

            $this->jsonResponse(['data' => $partido], self::HTTP_OK);
        } catch (PDOException $e) {
            error_log("Error a la base de dades: " . $e->getMessage());
            $this->jsonResponse(['error' => 'Error intern del servidor'], self::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
    }

    // POST /api/partidos - Crear
    public function apiCreatePartido()
    {
        $this->checkApiKey();
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Mètode no permès', self::HTTP_METHOD_NOT_ALLOWED);
            }

            $data = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON invàlid: ' . json_last_error_msg(), self::HTTP_BAD_REQUEST);
            }

            $this->validateMatchData($data);
            $this->validateTeamsAreDifferent($data);
            $this->validateDateFormat($data['fecha']);

            if (!crearPartit($this->conn, $data)) {
                throw new Exception('Error en crear el partit', self::HTTP_INTERNAL_ERROR);
            }

            $this->jsonResponse(['message' => 'Partit creat correctament'], self::HTTP_CREATED);
        } catch (PDOException $e) {
            error_log("Error a la base de dades: " . $e->getMessage());
            $this->jsonResponse(['error' => 'Error intern del servidor'], self::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
    }

    // PUT /api/partidos/{id} - Actualizar
    public function apiUpdatePartido($id)
    {
        $this->checkApiKey();
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
                throw new Exception('Mètode no permès', self::HTTP_METHOD_NOT_ALLOWED);
            }

            if (!is_numeric($id) || $id <= 0) {
                throw new Exception('ID de partit invàlid', self::HTTP_BAD_REQUEST);
            }

            $data = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON invàlid: ' . json_last_error_msg(), self::HTTP_BAD_REQUEST);
            }

            if (!consultarPartido($this->conn, $id)) {
                throw new Exception('Partit no trobat', self::HTTP_NOT_FOUND);
            }

            $this->validateMatchData($data);
            $this->validateTeamsAreDifferent($data);
            $this->validateDateFormat($data['fecha']);

            if (!actualitzarPartit($this->conn, $id, $data)) {
                throw new Exception('Error al actualizar el partido', self::HTTP_INTERNAL_ERROR);
            }

            $this->jsonResponse(['message' => 'Partido actualizado exitosamente'], self::HTTP_OK);
        } catch (PDOException $e) {
            error_log("Error en la base de datos: " . $e->getMessage());
            $this->jsonResponse(['error' => 'Error interno del servidor'], self::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
    }

    // DELETE /api/partidos/{id} - Eliminar
    public function apiDeletePartido($id)
    {
        $this->checkApiKey();
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->jsonResponse(['error' => 'Mètode no permès'], self::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            // Verificar que el partido existe
            $partido = consultarPartido($this->conn, $id);
            if (!$partido) {
                $this->jsonResponse(['error' => 'Partit no trobat'], self::HTTP_NOT_FOUND);
            }

            // Intentar eliminar el partido
            if (deletePartit($this->conn, $id)) {
                $this->jsonResponse(['message' => 'Partit eliminat correctament'], self::HTTP_OK);
            } else {
                $this->jsonResponse(['error' => 'Error al eliminar el partit'], self::HTTP_INTERNAL_ERROR);
            }
        } catch (PDOException $e) {
            error_log("Error en apiDeletePartido: " . $e->getMessage());
            $this->jsonResponse(['error' => 'Error intern del servidor'], self::HTTP_INTERNAL_ERROR);
        }
    }

    private function jsonResponse($data, $statusCode)
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        http_response_code($statusCode);
        echo json_encode([
            'status' => $statusCode < 300 ? 'success' : 'error',
            'timestamp' => date('c'),
            'data' => $data
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    private function validateMatchData($data)
    {
        $required = ['equipo_local', 'equipo_visitante', 'fecha', 'liga_id'];
        $missing = [];

        foreach ($required as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new Exception('Camps requerits que falten: ' . implode(', ', $missing), self::HTTP_BAD_REQUEST);
        }

        if (!is_numeric($data['liga_id']) || $data['liga_id'] <= 0) {
            throw new Exception('liga_id ha de ser un número positiu', self::HTTP_BAD_REQUEST);
        }
    }

    private function validateTeamsAreDifferent($data)
    {
        if ($data['equipo_local'] === $data['equipo_visitante']) {
            throw new Exception('L\'equip local i visitant no poden ser el mateix', self::HTTP_BAD_REQUEST);
        }
    }

    private function validateDateFormat($date)
    {
        $format = 'Y-m-d';
        $d = DateTime::createFromFormat($format, $date);
        if (!$d || $d->format($format) !== $date) {
            throw new Exception('Format de data invàlid. Utilitzeu YYYY-MM-DD', self::HTTP_BAD_REQUEST);
        }
    }

    // Nuevo método para validar la API Key consultando la base de datos
    private function checkApiKey()
    {
        $headers = getallheaders();
        if (!isset($headers['X-API-KEY'])) {
            $this->jsonResponse(['error' => 'API Key no proporcionada'], 401);
        }
        $providedKey = $headers['X-API-KEY'];
        require_once BASE_PATH . '/models/api/apiKey.model.php';
        if (!validarApiKey($this->conn, $providedKey)) {
            $this->jsonResponse(['error' => 'API Key no autorizada'], 401);
        }
    }
}
