<?php

/**
 * Controlador API per la gestió de partits
 * 
 * Aquesta classe gestiona totes les operacions CRUD relacionades amb els partits
 * a través d'una API RESTful. Implementa mètodes per llistar, consultar, crear,
 * actualitzar i eliminar partits.
 */
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



    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * GET /api/partidos
     * 
     * Endpoint per obtenir tots els partits. No requereix paràmetres.
     * Retorna un llistat complet de tots els partits disponibles.
     * 
     * @return void Retorna una resposta JSON amb tots els partits o un missatge d'error
     * @throws Exception Si el mètode HTTP no és GET
     * @throws PDOException Si hi ha un error en la base de dades
     */
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

    /**
     * GET /api/partidos/{id}
     * 
     * Endpoint per obtenir un partit específic mitjançant el seu ID.
     * 
     * @param int $id Identificador del partit
     * @return void Retorna una resposta JSON amb les dades del partit o un missatge d'error
     * @throws Exception Si l'ID no és vàlid o el partit no existeix
     */
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

    /**
     * POST /api/partidos
     * 
     * Endpoint per crear un nou partit.
     * Requereix un cos JSON amb: equipo_local, equipo_visitante, fecha, liga_id
     * 
     * @return void Retorna una resposta JSON confirmant la creació o un missatge d'error
     * @throws Exception Si les dades no són vàlides o hi ha errors en la creació
     */
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

    /**
     * PUT /api/partidos/{id}
     * 
     * Endpoint per actualitzar un partit existent.
     * Requereix un cos JSON amb: equipo_local, equipo_visitante, fecha, liga_id
     * 
     * @param int $id Identificador del partit a actualitzar
     * @return void Retorna una resposta JSON confirmant l'actualització o un missatge d'error
     * @throws Exception Si l'ID no és vàlid o les dades són incorrectes
     */
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

    /**
     * DELETE /api/partidos/{id}
     * 
     * Endpoint per eliminar un partit existent.
     * 
     * @param int $id Identificador del partit a eliminar
     * @return void Retorna una resposta JSON confirmant l'eliminació o un missatge d'error
     * @throws Exception Si el partit no es pot eliminar
     */
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

    /**
     * Genera una resposta JSON estandarditzada
     * 
     * @param mixed $data Les dades a retornar
     * @param int $statusCode El codi d'estat HTTP
     * @return void
     */
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

    /**
     * Valida les dades obligatòries d'un partit
     * 
     * @param array $data Dades del partit a validar
     * @throws Exception Si falten camps obligatoris o són invàlids
     */
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

    /**
     * Verifica que l'equip local i visitant siguin diferents
     * 
     * @param array $data Dades del partit
     * @throws Exception Si els equips són iguals
     */
    private function validateTeamsAreDifferent($data)
    {
        if ($data['equipo_local'] === $data['equipo_visitante']) {
            throw new Exception('L\'equip local i visitant no poden ser el mateix', self::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Valida el format de la data
     * 
     * @param string $date Data a validar
     * @throws Exception Si el format de la data no és vàlid
     */
    private function validateDateFormat($date)
    {
        $format = 'Y-m-d';
        $d = DateTime::createFromFormat($format, $date);
        if (!$d || $d->format($format) !== $date) {
            throw new Exception('Format de data invàlid. Utilitzeu YYYY-MM-DD', self::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Verifica la validesa de la API KEY
     * 
     * @throws Exception Si la API KEY no és vàlida o no s'ha proporcionat
     */
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
