(function () {
    const API_BASE = window.location.pathname.includes('/pantallas/') ? '../' : '';

    document.getElementById('search-button').addEventListener('click', function () {
        var query = document.getElementById('search-input').value.trim();

        if (query !== "") {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', `${API_BASE}php/productos/buscar_producto.php?q=` + encodeURIComponent(query), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log("Respuesta de la búsqueda: ", xhr.responseText);
                    document.getElementById('search-results').innerHTML = xhr.responseText;

                    const images = document.querySelectorAll('#search-results img');
                    images.forEach(img => {
                        console.log("Imagen cargada: ", img.src);
                    });
                }
            };
            xhr.send();
        } else {
            document.getElementById('search-results').innerHTML = "Por favor ingresa un término de búsqueda.";
        }
    });

    function realizarBusqueda() {
        const searchInput = document.getElementById('search-input').value.trim();
        const searchHeading = document.getElementById('search-heading');
        const searchResults = document.getElementById('search-results');

        if (searchInput.length === 0) {
            searchResults.innerHTML = '<p>Por favor, introduce un término de búsqueda.</p>';
            searchHeading.style.display = 'none';
            return;
        }

        fetch(`${API_BASE}php/productos/buscar_producto.php?q=${encodeURIComponent(searchInput)}`)
            .then(response => response.text())
            .then(data => {
                searchResults.innerHTML = data;

                if (data.trim() !== '') {
                    searchHeading.style.display = 'block';
                } else {
                    searchResults.innerHTML = '<p>No se encontraron productos para tu búsqueda.</p>';
                    searchHeading.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error al realizar la búsqueda:', error);
                searchResults.innerHTML = '<p>Hubo un error al realizar la búsqueda. Inténtalo de nuevo.</p>';
                searchHeading.style.display = 'none';
            });
    }
})();
