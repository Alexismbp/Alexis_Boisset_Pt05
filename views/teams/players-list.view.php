<?php
// Mapeo de ligas a league IDs con claves en minúsculas
$leagueMapping = [
    'laliga'          => 140,
    'premier league'  => 39,
    'ligue 1'         => 61
];
$leagueName = strtolower($_SESSION['lliga'] ?? 'laliga');
$leagueId = $leagueMapping[$leagueName] ?? 140;
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jugadores del Equipo</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/views/teams/styles_teams.css">
</head>

<body>
    <div class="container">
        <h1 id="teamName">Jugadores</h1>
        <div class="players-grid" id="playersContainer">
            <!-- Los jugadores se cargarán aquí dinámicamente -->
        </div>
        <a href="<?php echo BASE_URL; ?>teams" class="back-button">Volver a equipos</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Se utiliza la leagueId derivada de la sesión
            fetch(`<?php echo BASE_URL; ?>models/utils/FootballApi.php?league_id=<?php echo $leagueId; ?>`)
                .then(response => response.json())
                .then(data => {
                    const playersContainer = document.getElementById('playersContainer');
                    data.response.forEach(player => {
                        const playerCard = document.createElement('div');
                        playerCard.className = 'player-card';
                        playerCard.innerHTML = `
                            <img src="${player.player.photo}" alt="${player.player.name}" class="player-photo">
                            <h3>${player.player.name}</h3>
                            <p>Edad: ${player.player.age}</p>
                            <p>Posición: ${player.statistics[0].games.position || 'N/A'}</p>
                            <p>Nacionalidad: ${player.player.nationality}</p>
                        `;
                        playersContainer.appendChild(playerCard);
                    });
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>