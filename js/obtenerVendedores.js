(function () {
    const PAGE_BASE = window.location.pathname.includes('/pantallas/') ? '' : 'pantallas/';
    const API_BASE = window.location.pathname.includes('/pantallas/') ? '../' : '';

    let vendedoresMostrados = false;

    function obtenerVendedores() {
        const contenedor = document.getElementById('vendedores-container');

        if (vendedoresMostrados) {
            contenedor.style.display = 'none';
            document.getElementById('mostrar-vendedores-button').textContent = 'Mostrar Vendedores';
            vendedoresMostrados = false;
        } else {
            fetch(`${API_BASE}php/vendedores/obtener_vendedores.php`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener los vendedores.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        mostrarVendedores(data.vendedores);
                        contenedor.style.display = 'flex';
                        document.getElementById('mostrar-vendedores-button').textContent = 'Ocultar Vendedores';
                        vendedoresMostrados = true;
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }

    function mostrarVendedores(vendedores) {
        const contenedor = document.getElementById('vendedores-container');
        contenedor.innerHTML = '';

        if (vendedores.length === 0) {
            contenedor.innerHTML = '<p>No hay vendedores disponibles.</p>';
            return;
        }

        vendedores.forEach(vendedor => {
            const vendedorItem = document.createElement('div');
            vendedorItem.innerHTML = `
                <h3 style="cursor:pointer; color:blue; text-decoration:underline;">${vendedor.nombre_usuario}</h3>
                <p>Email: ${vendedor.correo}</p>
                <hr>
            `;

            vendedorItem.onclick = function() {
                irAPerfil(vendedor.id);
            };

            contenedor.appendChild(vendedorItem);
        });
    }

    function irAPerfil(vendedorId) {
        window.location.href = `${PAGE_BASE}Perfil.php?id=${vendedorId}`;
    }

    window.obtenerVendedores = obtenerVendedores;
    window.irAPerfil = irAPerfil;
})();
