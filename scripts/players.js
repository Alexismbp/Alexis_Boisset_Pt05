/**
 * Gestió de la visualització de jugadors d'un equip
 *
 * @author Alexis Boisset
 *
 * Funcionalitats principals:
 * - Càrrega dinàmica dels jugadors d'un equip específic
 * - Mostra la informació bàsica de cada jugador:
 *   - Foto del jugador (amb imatge per defecte si no està disponible)
 *   - Nom del jugador
 *   - Posició al camp
 * - Gestió d'errors en la càrrega de dades
 *
 * @param {string} baseUrl - URL base de l'aplicació
 * @param {number} teamId - Identificador de l'equip
 */

// Alexis Boisset
function loadPlayers(baseUrl, teamId) {
  const container = document.getElementById("playersContainer");
  container.innerHTML = "<p>Cargando jugadores...</p>";

  fetch(
    `${baseUrl}controllers/api/FootballApi.php?action=teamPlayers&team_id=${teamId}`
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.response && data.response.length > 0) {
        container.innerHTML = "";
        data.response.forEach((player) => {
          container.innerHTML += `
                        <div class="player-card">
                            <img src="${player.player.photo}" alt="${
            player.player.name
          }" class="player-photo"
                                 onerror="this.src='${baseUrl}assets/img/default-player.png'">
                            <h3 class="player-name">${player.player.name}</h3>
                            <p class="player-position">Posición: ${
                              player.statistics[0].games.position || "N/A"
                            }</p>
                        </div>
                    `;
        });
      } else {
        container.innerHTML = "<p>No se encontraron jugadores</p>";
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      container.innerHTML = "<p>Error al cargar los jugadores</p>";
    });
}
