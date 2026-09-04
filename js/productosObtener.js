(function () {
  const APP_PATHS = (() => {
    const inScreens = window.location.pathname.includes('/pantallas/');
    return {
      PAGE_BASE: inScreens ? '' : 'pantallas/',
      API_BASE: inScreens ? '../' : ''
    };
  })();

  const { API_BASE } = APP_PATHS;

  function cargarProductos(endpoint, selectorContenedor, mensajeVacio) {
    fetch(`${API_BASE}php/productos/${endpoint}`)
      .then((response) => {
        if (!response.ok) throw new Error("Network response was not ok");
        return response.text();
      })
      .then((html) => {
        document.querySelector(selectorContenedor).innerHTML = html;
      })
      .catch((error) => {
        console.error(`Error al obtener ${endpoint}:`, error);
        document.querySelector(selectorContenedor).innerHTML = `<p>${mensajeVacio}</p>`;
      });
  }

  function obtenerproductosDestacados() {
    cargarProductos(
      'get_productos_destacados.php',
      '.products_destacados',
      'No se pudieron cargar los productos destacados.'
    );
  }

  function obtenerproductosRecientes() {
    cargarProductos(
      'get_productos_recientes.php',
      '.products_recientes',
      'No se pudieron cargar los productos recientes.'
    );
  }

  window.onload = function () {
    obtenerproductosDestacados();
    obtenerproductosRecientes();
  };

  window.obtenerproductosDestacados = obtenerproductosDestacados;
  window.obtenerproductosRecientes = obtenerproductosRecientes;
})();