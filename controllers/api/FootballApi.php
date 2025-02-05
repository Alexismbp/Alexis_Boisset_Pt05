<?php
require_once __DIR__ . '/../../models/env.php';

// Procesar petición AJAX
if (isset($_GET['action']) && $_GET['action'] === 'teamPlayers' && isset($_GET['team_id'])) {
    $api = new FootballApi();
    $response = $api->getTeamPlayers($_GET['team_id']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

class FootballApi
{
    private const API_KEY = FOOTBALL_API_KEY;
    private const API_HOST = API_HOST;
    private const SEASON = '2023';
    private const CACHE_DURATION = 3600; // Duración del caché en segundos (1 hora)
    private const CACHE_DIR = __DIR__ . '/cache'; // Directorio para almacenar los archivos de caché

    private static $leagueIds = [
        'premierleague' => 39,
        'laliga' => 140,
        'ligue1' => 61
    ];

    public function __construct()
    {
        // Crear directorio de caché si no existe
        if (!file_exists(self::CACHE_DIR)) {
            mkdir(self::CACHE_DIR, 0755, true);
        }
    }

    private function executeApiCall(string $endpoint, array $params): array
    {
        $cacheKey = md5($endpoint . serialize($params));
        $cacheFile = self::CACHE_DIR . '/' . $cacheKey . '.json';

        // Verificar si existe el caché y si no ha expirado
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < self::CACHE_DURATION) {
            $cachedResponse = file_get_contents($cacheFile);
            return json_decode($cachedResponse, true) ?? [];
        }

        $url = "https://" . self::API_HOST . "/$endpoint?" . http_build_query($params);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'x-rapidapi-key: ' . self::API_KEY,
                'x-rapidapi-host: ' . self::API_HOST
            ],

            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CAINFO => BASE_PATH . 'controllers/api/cacert.pem',


        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            error_log("Curl Error: " . $err);
            throw new Exception("Error en la llamada a la API: $err");
        }

        if ($httpCode !== 200) {
            throw new Exception("API respondió con código: $httpCode");
        }

        $decodedResponse = json_decode($response, true) ?? [];

        // Guardar la respuesta en caché
        file_put_contents($cacheFile, $response);

        return $decodedResponse;
    }

    public function getTeams(int $leagueId): array
    {
        try {
            $response = $this->executeApiCall('teams', [
                'league' => $leagueId,
                'season' => self::SEASON
            ]);

            // Cambiar la validación ya que la API devuelve un array vacío en errors cuando todo va bien
            if (!isset($response['response'])) {
                throw new Exception('Respuesta de API inválida');
            }

            if (isset($response['errors']) && !empty($response['errors'])) {
                error_log('API Error: ' . json_encode($response['errors']));
                throw new Exception('Error en la respuesta de la API');
            }

            return $response;
        } catch (Exception $e) {
            error_log('FootballApi Error: ' . $e->getMessage());
            return [
                'error' => 'Error obteniendo equipos',
                'details' => $e->getMessage(),
                'league_id' => $leagueId
            ];
        }
    }

    public function getTeamPlayers(int $teamId): array
    {
        try {
            return $this->executeApiCall('players', [
                'team' => $teamId,
                'season' => self::SEASON
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ['error' => 'Error obteniendo jugadores'];
        }
    }

    public static function getLeagueId(string $leagueName): ?int
    {
        return self::$leagueIds[$leagueName] ?? null;
    }
}
