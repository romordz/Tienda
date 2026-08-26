document.getElementById('btn-edit').addEventListener('click', function () {
    var editForm = document.getElementById('edit-form');
    if (editForm.style.display === 'none') {
        editForm.style.display = 'block';
    } else {
        editForm.style.display = 'none';
    }
});

document.getElementById('btn-crear-lista').addEventListener('click', function() {
    document.getElementById('popup-crear-lista').style.display = 'block';
});

document.getElementById('close-popup').addEventListener('click', function() {
    document.getElementById('popup-crear-lista').style.display = 'none';
});

document.getElementById('close-popup-detalle').addEventListener('click', function() {
    document.getElementById('popup-detalle-lista').style.display = 'none';
});

window.onclick = function(event) {
    const popup = document.getElementById('popup-crear-lista');
    if (event.target === popup) {
        popup.style.display = 'none';
    }
}

function mostrarDetallesLista(listaId) {
    var detalleLista = document.getElementById('contenido-detalle-lista');
    detalleLista.setAttribute('data-lista-id', listaId);
    detalleLista.innerHTML = "Cargando detalles...";

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/obtener_detalles_lista.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                console.log("Respuesta del servidor:", response);
                if (response.success) {
                    var productosHtml = 
                        `<table class="productos-table">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>`;

                    response.productos.forEach(function(producto) {
                        var imgSrc = producto.imagenes ? 'data:image/jpeg;base64,' + producto.imagenes : '';
                        var imagenesJson = producto.imagenes_json ? producto.imagenes_json : [];

                        var imagenesHtml = '';
                        if (imagenesJson.length > 0) {
                            imagenesHtml += imagenesJson.map(function(imagen) {
                                return `<img src="data:image/jpeg;base64,${imagen}" alt="Imagen de ${producto.nombre}" class="producto-imagen">`;
                            }).join('');
                        }

                        var productoImagenHtml = imagenesJson ? `<div>${imagenesHtml}</div>` : '';

                        var precioHtml = producto.precio ? `$${parseFloat(producto.precio).toFixed(2)}` : "Para cotizar";

                        productosHtml += 
                            `<tr>
                                <td>${productoImagenHtml}</td>
                                <td>${producto.nombre}</td>
                                <td>${producto.descripcion}</td>
                                <td>${precioHtml}</td>
                                <td>${isOwner ? `<button class="btn-eliminar" data-id="${producto.id}">Eliminar</button>` : 'Acción no permitida'} </td>
                            </tr>`;
                    });

                    productosHtml += 
                        `</tbody>
                    </table>`;

                    detalleLista.innerHTML = 
                        `<h3>${response.lista.nombre_lista}</h3>
                        <p>${response.lista.descripcion}</p>
                        <p>Privacidad: ${response.lista.privacidad}</p>
                        <h4>productos en la lista:</h4>
                        ${productosHtml}`;
                    console.log("exitoso!")
                } else {
                    detalleLista.innerHTML = "Error: " + response.message;
                }
            } else {
                detalleLista.innerHTML = "Error en la respuesta del servidor.";
            }
        }
    };

    xhr.send('lista_id=' + encodeURIComponent(listaId));
    document.getElementById('popup-detalle-lista').style.display = 'block';
}

window.onclick = function(event) {
    const popupCrear = document.getElementById('popup-crear-lista');
    const popupDetalle = document.getElementById('popup-detalle-lista');
    
    if (event.target === popupCrear) {
        popupCrear.style.display = 'none';
    }
    
    if (event.target === popupDetalle) {
        popupDetalle.style.display = 'none';
    }
}

 document.addEventListener('click', function(event) {
    if (event.target.classList.contains('btn-eliminar')) {
        const productoId = event.target.getAttribute('data-id');
        const listaId = document.getElementById('contenido-detalle-lista').getAttribute('data-lista-id');
        
        if (confirm('¿Estás seguro de que quieres eliminar este producto de la lista?')) {
            eliminarProducto(productoId, listaId);
        }
    }
});

function eliminarProducto(productoId, listaId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/eliminar_producto_lista.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert('Producto eliminado con éxito.');
                    mostrarDetallesLista(response.lista_id);
                } else {
                    alert('Error al eliminar el producto: ' + response.message);
                }
            } else {
                alert('Error en la respuesta del servidor.');
            }
        }
    };

    xhr.send('producto_id=' + encodeURIComponent(productoId) + '&lista_id=' + encodeURIComponent(listaId));
}