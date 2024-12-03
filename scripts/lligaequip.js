// Alexis Boisset

// Objecte d'arrays d'equips per lliga (tipic de JS)
const equipsPerLiga = {
    "LaLiga": [
        "FC Barcelona", "Real Madrid", "Atlético de Madrid", "Sevilla FC", "Valencia CF", "Villarreal CF",
        "Athletic Club", "Girona FC", "Real Sociedad", "Real Betis", "Rayo Vallecano", "Celta de Vigo",
        "CA Osasuna", "RCD Mallorca", "UD Almería", "Getafe CF", "UD Las Palmas", "Deportivo Alavés", "Granada CF"
    ],
    "Premier League": [
        "Manchester United", "Manchester City", "Chelsea", "Liverpool", "Arsenal", "Tottenham",
        "Leicester City", "West Ham United", "Everton", "Wolverhampton", "Newcastle United",
        "Southampton", "Aston Villa", "Crystal Palace", "Brighton", "Burnley", "Brentford", "Sheffield United"
    ],
    "Ligue 1": [
        "Paris Saint-Germain", "Olympique Lyonnais", "Olympique de Marseille", "AS Monaco", "Lille OSC",
        "Stade Rennais", "OGC Nice", "RC Strasbourg", "Montpellier HSC", "Stade de Reims"
    ]
};
/* @param equipEscollit
    només s'utilitza en el register.view.php per escollir l'equip favorit
    aquest permetra fer un <option selected> a l'option que porti l'equip favorit */

// Funció que actualitza els equips dels <option> segons la lliga seleccionada
function actualitzarEquips(vista, equipEscollit) {
    
    // Agafem elements de l'HTML
    const ligaSelect = document.getElementById("lliga");
    const equipLocal = document.getElementById("equip_local");
    const equipVisitant = document.getElementById("equip_visitant");
    const equipSelect = document.getElementById("equip");

   // Agafem el valor de la lliga seleccionada
    const ligaSeleccionada = ligaSelect.value;

    if (vista == "registrar") {

        // Netejar <option> per introduir els nous valors
        equipSelect.innerHTML = '<option value="">-- Selecciona l\'equip --</option>';

        // Agregar equips segons lliga
        if (ligaSeleccionada && equipsPerLiga[ligaSeleccionada]) {
            equipsPerLiga[ligaSeleccionada].forEach(function (equip) {
                const option = document.createElement("option");
                option.value = equip;
                option.selected = true;
                option.text = equip;

                // Compara si l'equip que esta carregant el foreach es el favorit
                if (equipEscollit === equip) {
                    option.selected = true;
                } else {
                    option.selected = false;
                }

                // Inserim <option>
                equipSelect.appendChild(option);
            })
        }

    // Només per la vista match-edit.view.php, funció molt més general, només filtra per Lliga.
    } else if (vista == "crear") {
        // Netejar <option> per introduir els nous valors
        equipLocal.innerHTML = '<option value="">-- Selecciona l\'equip local --</option>';
        equipVisitant.innerHTML = '<option value="">-- Selecciona l\'equip visitant --</option>';

        // Agregar equips segons lliga
        if (ligaSeleccionada && equipsPerLiga[ligaSeleccionada]) {
            equipsPerLiga[ligaSeleccionada].forEach(function (equip) {
                const optionLocal = document.createElement("option");
                const optionVisitant = document.createElement("option");
                optionLocal.value = equip;
                optionVisitant.value = equip;
                optionLocal.text = equip;
                optionVisitant.text = equip;
                equipLocal.appendChild(optionLocal);
                equipVisitant.appendChild(optionVisitant);
            });
        }
    } else if (vista == "profile") {
        // Guardar el equipo actual antes de limpiar el select
        const currentEquip = equipEscollit; // Usamos el equipo pasado como parámetro
        equipSelect.innerHTML = '<option value="">-- Selecciona l\'equip --</option>';

        // Agregar equipos según liga seleccionada
        if (ligaSeleccionada && equipsPerLiga[ligaSeleccionada]) {
            equipsPerLiga[ligaSeleccionada].forEach(function (equip) {
                const option = document.createElement("option");
                option.value = equip;
                option.text = equip;
                
                // Marcar como seleccionado si es el equipo actual
                if (equip === currentEquip) {
                    option.selected = true;
                }
                
                equipSelect.appendChild(option);
            });
        }
    }
}

// Ejecutar actualitzarEquips al cargar la página para mostrar los equipos de la liga actual
document.addEventListener('DOMContentLoaded', function() {
    const ligaSelect = document.getElementById("lliga");
    if (ligaSelect && ligaSelect.value) {
        actualitzarEquips('profile', document.getElementById("equip").value);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const ligaSelect = document.getElementById("lliga");
    const equipVisitant = document.getElementById("equip_visitant");
    const equipLocal = document.getElementById("equip_local");

    // Si estamos en la página de crear partido y tenemos la liga del usuario
    if (equipVisitant && equipLocal && equipLocal.value) {
        const ligaValue = document.getElementById("lliga").value;
        const equipLocalValue = equipLocal.value;

        // Llenar el select de equipos visitantes con los equipos de la liga
        if (ligaValue && equipsPerLiga[ligaValue]) {
            equipsPerLiga[ligaValue].forEach(function(equip) {
                if (equip !== equipLocalValue) { // Excluir el equipo local
                    const option = document.createElement("option");
                    option.value = equip;
                    option.text = equip;
                    equipVisitant.appendChild(option);
                }
            });
        }
    }
});


