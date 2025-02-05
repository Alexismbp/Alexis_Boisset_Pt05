<?php
require_once BASE_PATH . 'controllers/api/FootballApi.php';

/**
 * Determina el ID de la liga según la liga del usuario en sesión
 */
function determineLeagueId(): int
{
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $leagueName = strtolower(str_replace(' ', '', $_SESSION['lliga']));
        return FootballApi::getLeagueId($leagueName) ?? 140; // 140 es LaLiga por defecto
    }
    return 140; // LaLiga como valor predeterminado
}

try {
    // Crear instancia de la API
    $api = new FootballApi();

    // Obtener el ID de la liga
    $leagueId = determineLeagueId();

    // Obtener los equipos usando la nueva clase con caché
    $teams = $api->getTeams($leagueId);

    // Si hay error, guardarlo en session para mostrarlo
    if (isset($teams['error'])) {
        $_SESSION['failure'] = $teams['error'];
        if (isset($teams['details'])) {
            error_log("Error detallado: " . $teams['details']);
        }
    }

    // Incluir la vista con los datos
    include BASE_PATH . 'views/teams/teams-list.view.php';
} catch (Exception $e) {
    $_SESSION['failure'] = "Error al procesar la solicitud: " . $e->getMessage();
    // Redirigir a la página principal o mostrar un error
    header('Location: ' . BASE_URL);
    exit;
}
