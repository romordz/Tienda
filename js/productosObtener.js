function obtenerproductosDestacados() {
    fetch('php/get_productos_destacados.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(productos => {
            console.log("productos destacados obtenidos:", productos);

            const productsContainer = document.querySelector('.products_destacados');
            productsContainer.innerHTML = '';

            if (productos.error) {
                productsContainer.innerHTML = `<p>${productos.error}</p>`;
                return;
            }

            if (productos.length === 0) {
                productsContainer.innerHTML = '<p>No hay productos destacados disponibles.</p>';
            } else {
                productos.forEach(producto => {
                    const imagenes = JSON.parse(producto.imagenes_json);
                    const imagenPrincipal = imagenes.length > 0 ? (imagenes[0].startsWith('http') ? imagenes[0] : `data:image/jpeg;base64,${imagenes[0]}`) : 'Recursos/default.jpg';

                    const priceDisplay = producto.para_cotizar == 1 
                        ? `<span class="price">Cotización disponible</span>` 
                        : `<span class="price">$${parseFloat(producto.precio).toFixed(2)}</span>`;

                    const productItem = `
                        <div class="product-item" onclick="checkSession('producto_detalle.php?id=<?php echo $producto['id']; ?>');">
                            <a href="javascript:void(0);">
                                <img src="${imagenPrincipal}" alt="${producto.nombre}">
                            </a>
                            <h3>${producto.nombre}</h3>
                            <p>${producto.descripcion}</p>
                            ${priceDisplay}
                        </div>
                    `;
                    productsContainer.innerHTML += productItem;
                });
            }
        })
        .catch(error => console.error('Error al obtener productos destacados:', error));
}


function obtenerproductosRecientes() {
    fetch('php/get_productos_recientes.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(productos => {
            console.log("productos recientes obtenidos:", productos);

            const productsContainer = document.querySelector('.products_recientes');
            productsContainer.innerHTML = '';

            if (productos.error) {
                productsContainer.innerHTML = `<p>${productos.error}</p>`;
                return;
            }

            if (productos.length === 0) {
                productsContainer.innerHTML = '<p>No hay productos recientes disponibles.</p>';
            } else {
                productos.forEach(producto => {
                    const imagenes = JSON.parse(producto.imagenes_json);
                    const imagenPrincipal = imagenes.length > 0 
    ? (imagenes[0].startsWith('http') ? imagenes[0] : `data:image/jpeg;base64,${imagenes[0]}`)
    : 'Recursos/default.jpg';

                    const priceDisplay = producto.para_cotizar == 1 
                        ? `<span class="price">Cotización disponible</span>` 
                        : `<span class="price">$${parseFloat(producto.precio).toFixed(2)}</span>`;

                    const productItem = `
                        <div class="product-item" onclick="checkSession('producto_detalle.php?id=<?php echo $producto['id']; ?>');">
                            <a href="javascript:void(0);">
                                <img src="${imagenPrincipal}" alt="${producto.nombre}">
                            </a>
                            <h3>${producto.nombre}</h3>
                            <p>${producto.descripcion}</p>
                            ${priceDisplay}
                        </div>
                    `;
                    productsContainer.innerHTML += productItem;
                });
            }
        })
        .catch(error => console.error('Error al obtener productos recientes:', error));
}


window.onload = function() {
    obtenerproductosDestacados();
    obtenerproductosRecientes();
};
