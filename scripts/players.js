function loadPlayers(baseUrl, teamId) {
  const container = document.getElementById("playersContainer");
  container.innerHTML = "<p>Cargando jugadores...</p>";

  fetch(
    `${baseUrl}models/utils/FootballApi.php?action=teamPlayers&team_id=${teamId}`
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
