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
    private const CACHE_DURATION = 3600; // Duración del caché (1 hora)
    private const CACHE_DIR = __DIR__ . '/cache'; // Directorio para almacenar los archivos de caché

    private static $leagueIds = [
        'premierleague' => 39,
        'laliga' => 140,
        'ligue1' => 61
    ];

    public function __construct()
    {
        // Verificar que las constantes están definidas
        error_log('API_KEY: ' . (defined('FOOTBALL_API_KEY') ? 'definida' : 'no definida'));
        error_log('API_HOST: ' . (defined('API_HOST') ? 'definido' : 'no definido'));

        // Verificar permisos del directorio cache
        if (!file_exists(self::CACHE_DIR)) {
            error_log('Intentando crear directorio cache en: ' . self::CACHE_DIR);
            $result = @mkdir(self::CACHE_DIR, 0755, true);
            if (!$result) {
                error_log('Error creando directorio cache: ' . error_get_last()['message']);
            }
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
        error_log('Llamando a API: ' . $url);

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

        // Loggear información detallada de la respuesta
        if ($err) {
            error_log("Curl Error: " . $err);
            error_log("Curl Info: " . print_r(curl_getinfo($curl), true));
            throw new Exception("Error en la llamada a la API: $err");
        }

        if ($httpCode !== 200) {
            error_log("API Response Code: " . $httpCode);
            error_log("API Response Body: " . $response);
            error_log("API Full Response: " . print_r(curl_getinfo($curl), true));
            throw new Exception("API respondió con código: $httpCode");
        }

        // Verificar que la respuesta es JSON válido
        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error: " . json_last_error_msg());
            error_log("Response raw: " . $response);
        }

        // Guardar la respuesta en caché
        try {
            file_put_contents($cacheFile, $response);
        } catch (Exception $e) {
            error_log("Error guardando cache: " . $e->getMessage());
        }

        return $decodedResponse;
    }

    public function getTeams(int $leagueId): array
    {
        try {
            $response = $this->executeApiCall('teams', [
                'league' => $leagueId,
                'season' => self::SEASON
            ]);


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
            error_log("Solicitando jugadores para equipo ID: " . $teamId);
            $response = $this->executeApiCall('players', [
                'team' => $teamId,
                'season' => self::SEASON
            ]);
            error_log("Respuesta de jugadores: " . print_r($response, true));
            return $response;
        } catch (Exception $e) {
            error_log("Error obteniendo jugadores: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return ['error' => 'Error obteniendo jugadores'];
        }
    }

    public static function getLeagueId(string $leagueName): ?int
    {
        return self::$leagueIds[$leagueName] ?? null;
    }
}
