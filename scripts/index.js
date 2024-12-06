// Alexis Boisset
// Funcion para mostrar y ocultar el dropdown (desplegable) de la barra de navegacion
function toggleDropdown() {
    document.getElementById("dropdown-content").classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}