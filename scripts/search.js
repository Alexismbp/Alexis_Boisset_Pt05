document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchBar');
    const searchResults = document.getElementById('searchResults');
    const base_url = window.location.origin + window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));
    let timeoutId;

    searchInput.addEventListener('input', (e) => {
        clearTimeout(timeoutId);
        
        timeoutId = setTimeout(() => {
            const searchTerm = e.target.value;
            console.log(`Término de búsqueda: ${searchTerm}`); // Debug

            if (searchTerm.length < 3) {
                searchResults.innerHTML = '';
                searchResults.style.display = 'none';
                return;
            }

            fetch(`${base_url}/search?term=${encodeURIComponent(searchTerm)}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data); // Debug
                searchResults.innerHTML = '';
                
                if (data.error) {
                    searchResults.innerHTML = `<p>${data.error}</p>`;
                    searchResults.style.display = 'block';
                    return;
                }

                if (data.length === 0) {
                    searchResults.style.display = 'none';
                    return;
                }

                data.forEach(partit => {
                    const div = document.createElement('div');
                    div.className = 'search-result';
                    div.innerHTML = `
                        <h4>${partit.equip_local} vs ${partit.equip_visitant}</h4>
                        <p>Fecha: ${new Date(partit.data).toLocaleDateString()}</p>
                    `;
                    div.addEventListener('click', () => {
                        window.location.href = `${base_url}/view-match/${partit.id}`;
                    });
                    searchResults.appendChild(div);
                });
                
                searchResults.style.display = 'block';
            })
            .catch(error => {
                console.error('Error en la búsqueda:', error);
                searchResults.innerHTML = `<p>Error al realizar la búsqueda.</p>`;
                searchResults.style.display = 'block';
            });
        }, 300);
    });

    // Ocultar resultados al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});