(function () {
    const API_BASE = window.location.pathname.includes('/pantallas/') ? '../' : '';

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('btn-agregar-categoria').addEventListener('click', function() {
            document.getElementById('popup-agregar-categoria').style.display = 'flex';
        });

        document.querySelector('.close-popup').addEventListener('click', function() {
            cerrarPopup();
        });

        document.getElementById('btn-guardar-categoria').addEventListener('click', async function() {
            const nombre = document.getElementById('nombre_categoria').value;
            const descripcion = document.getElementById('descripcion_categoria').value;

            try {
                const response = await fetch(`${API_BASE}php/categorias/process_agregar_categoria.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ nombre, descripcion })
                });

                const result = await response.json();
                if (result.success) {
                    cerrarPopup();
                    cargarcategorias();
                } else {
                    alert('Error al agregar la categoría: ' + result.error);
                }
            } catch (error) {
                console.error('Error al agregar la categoría:', error);
                alert('Error al agregar la categoría. Intenta de nuevo.');
            }
        });
    });

    function cerrarPopup() {
        document.getElementById('popup-agregar-categoria').style.display = 'none';
    }
})();
