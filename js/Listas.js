(function () {
  const APP_PATHS = (() => {
    const inScreens = window.location.pathname.includes("/pantallas/");
    return {
      API_BASE: inScreens ? "../" : "",
    };
  })();

  const { API_BASE } = APP_PATHS;

  const btnCrearLista = document.getElementById("btn-crear-lista");
  if (btnCrearLista) {
    btnCrearLista.addEventListener("click", function () {
      document.getElementById("popup-crear-lista").style.display = "block";
    });
  }

  const closePopup = document.getElementById("close-popup");
  if (closePopup) {
    closePopup.addEventListener("click", function () {
      document.getElementById("popup-crear-lista").style.display = "none";
    });
  }

  const closePopupDetalle = document.getElementById("close-popup-detalle");
  if (closePopupDetalle) {
    closePopupDetalle.addEventListener("click", function () {
      document.getElementById("popup-detalle-lista").style.display = "none";
    });
  }

  window.onclick = function (event) {
    const popupCrear = document.getElementById("popup-crear-lista");
    const popupDetalle = document.getElementById("popup-detalle-lista");

    if (popupCrear && event.target === popupCrear) {
      popupCrear.style.display = "none";
    }
    if (popupDetalle && event.target === popupDetalle) {
      popupDetalle.style.display = "none";
    }
  };

  function mostrarDetallesLista(listaId) {
    var detalleLista = document.getElementById("contenido-detalle-lista");
    detalleLista.setAttribute("data-lista-id", listaId);
    detalleLista.innerHTML = "Cargando detalles...";

    var previewElement = document.querySelector(
      `.lista-preview[onclick*="${listaId}"]`,
    );
    var isOwner = previewElement
      ? previewElement.getAttribute("data-is-owner") === "true"
      : false;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", `${API_BASE}php/listas/obtener_detalles_lista.php`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        if (xhr.status == 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.success) {
            var productosHtml = `<table class="productos-table">
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

            response.productos.forEach(function (producto) {
              var imagenesJson = producto.imagenes_json
                ? producto.imagenes_json
                : [];

              var imagenesHtml = "";
              if (imagenesJson.length > 0) {
                imagenesHtml =
                  '<div class="producto-imagen-stack">' +
                  imagenesJson
                    .map(function (imagen, i) {
                      var src = imagen.startsWith("http")
                        ? imagen
                        : "data:image/jpeg;base64," + imagen;
                      var offset = i * 8;
                      var zIndex = imagenesJson.length - i;
                      return `<img src="${src}" alt="Imagen de ${producto.nombre}" style="top:${offset}px; left:${offset}px; z-index:${zIndex};">`;
                    })
                    .join("") +
                  "</div>";
              }

              var precioHtml = producto.precio
                ? `$${parseFloat(producto.precio).toFixed(2)}`
                : "Para cotizar";
              productosHtml += `<tr class="producto-row" data-id="${producto.id}">
                                    <td>${imagenesHtml}</td>
                                    <td>${producto.nombre}</td>
                                    <td>${producto.descripcion}</td>
                                    <td>${precioHtml}</td>
                                    <td>${isOwner ? `<button class="btn-eliminar" data-id="${producto.id}">Eliminar</button>` : "Acción no permitida"} </td>
                                </tr>`;
            });

            productosHtml += `</tbody></table>`;

            detalleLista.innerHTML = `<h3>${response.lista.nombre_lista}</h3>
                            <p>${response.lista.descripcion}</p>
                            <p>Privacidad: ${response.lista.privacidad}</p>
                            ${productosHtml}`;
          } else {
            detalleLista.innerHTML = "Error: " + response.message;
          }
        } else {
          detalleLista.innerHTML = "Error en la respuesta del servidor.";
        }
      }
    };

    xhr.send("lista_id=" + encodeURIComponent(listaId));
    document.getElementById("popup-detalle-lista").style.display = "block";
  }

  document.addEventListener("click", function (event) {
    if (event.target.classList.contains("btn-eliminar")) {
      const productoId = event.target.getAttribute("data-id");
      const listaId = document
        .getElementById("contenido-detalle-lista")
        .getAttribute("data-lista-id");

      if (
        confirm(
          "¿Estás seguro de que quieres eliminar este producto de la lista?",
        )
      ) {
        eliminarProducto(productoId, listaId);
      }
      return;
    }

    const row = event.target.closest(".producto-row");
    if (row) {
      const productoId = row.getAttribute("data-id");
      window.location.href =
        "producto_detalle.php?id=" + encodeURIComponent(productoId);
    }
  });

  function eliminarProducto(productoId, listaId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", `${API_BASE}php/listas/eliminar_producto_lista.php`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        if (xhr.status == 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.success) {
            alert("Producto eliminado con éxito.");
            mostrarDetallesLista(response.lista_id);
          } else {
            alert("Error al eliminar el producto: " + response.message);
          }
        } else {
          alert("Error en la respuesta del servidor.");
        }
      }
    };

    xhr.send(
      "producto_id=" +
        encodeURIComponent(productoId) +
        "&lista_id=" +
        encodeURIComponent(listaId),
    );
  }

  function editarLista(id, nombre, descripcion, privacidad) {
    document.getElementById("edit-lista-id").value = id;
    document.getElementById("edit-nombre").value = nombre;
    document.getElementById("edit-descripcion").value = descripcion;
    document.getElementById("edit-privacidad").value = privacidad;
    document.getElementById("popup-editar-lista").style.display = "block";

    console.log("Editando lista:", id, nombre);
  }

  function borrarLista(id) {
    if (confirm("¿Estás seguro de que quieres borrar esta lista?")) {
      fetch(`../php/listas/eliminar_lista.php?id=${id}`, {
        method: "GET",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => response.text())
        .then((data) => {
          if (data.trim() === "success") {
            location.reload();
          } else {
            alert("Error al borrar la lista: " + data);
          }
        })
        .catch((error) => console.error("Error:", error));
    }
  }

  function guardarEdicionLista() {
    var formData = new FormData(document.getElementById("form-editar-lista"));
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/listas/actualizar_lista.php", true);

    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

    xhr.onload = function () {
      if (xhr.status === 200) {
        var data = xhr.responseText.trim();
        if (data === "success") {
          location.reload();
        } else {
          alert("Error al actualizar la lista: " + data);
        }
      } else {
        alert("Error en la respuesta del servidor.");
      }
    };
    xhr.send(formData);
  }

  window.mostrarDetallesLista = mostrarDetallesLista;
  window.editarLista = editarLista;
  window.borrarLista = borrarLista;
  window.guardarEdicionLista = guardarEdicionLista;
})();
