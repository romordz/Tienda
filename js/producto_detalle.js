(function () {
  const APP_PATHS = (() => {
    const inScreens = window.location.pathname.includes('/pantallas/');
    return {
      API_BASE: inScreens ? '../' : ''
    };
  })();

  const { API_BASE } = APP_PATHS;

  let currentIndex = 0;

  function moveCarousel(direction) {
    const items = document.querySelectorAll(".carousel-item");
    currentIndex += direction;

    if (currentIndex < 0) {
      currentIndex = items.length - 1;
    } else if (currentIndex >= items.length) {
      currentIndex = 0;
    }

    const offset = -currentIndex * 100;
    document.querySelector(
      ".carousel-inner"
    ).style.transform = `translateX(${offset}%)`;

    items.forEach((item) => item.classList.remove("active"));
    items[currentIndex].classList.add("active");
  }

  document.getElementById("btn-agregar").addEventListener("click", function () {
    var productoId = document.querySelector('input[name="producto_id"]').value;
    var listaId = document.querySelector('select[name="lista_id"]').value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", `${API_BASE}php/listas/agregar_a_lista.php`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          alert("Producto agregado a la lista correctamente.");
          document.getElementById("popup-seleccionar-lista").style.display =
            "none";
        } else {
          alert("Error: " + response.message);
        }
      }
    };

    xhr.send("producto_id=" + productoId + "&lista_id=" + listaId);
  });

  document
    .getElementById("btn-agregar-lista")
    .addEventListener("click", function () {
      document.getElementById("popup-seleccionar-lista").style.display = "block";
    });

  document.getElementById("close-popup").addEventListener("click", function () {
    document.getElementById("popup-seleccionar-lista").style.display = "none";
  });

  window.onclick = function (event) {
    var popup = document.getElementById("popup-seleccionar-lista");
    if (event.target === popup) {
      popup.style.display = "none";
    }
  };

  function rateProduct(productId, ratingType) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", `${API_BASE}php/productos/rate_producto.php`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
        } else {
        }
        location.reload();
      }
    };
    xhr.send("id=" + productId + "&rating=" + ratingType);
  }

  window.moveCarousel = moveCarousel;
  window.rateProduct = rateProduct;
})();
