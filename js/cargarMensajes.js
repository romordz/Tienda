(function () {
  const APP_PATHS = (() => {
    const inScreens = window.location.pathname.includes('/pantallas/');
    return {
      API_BASE: inScreens ? '../' : ''
    };
  })();

  const { API_BASE } = APP_PATHS;

  function cargarmensajes() {
    var productoId = document.getElementById("producto_id").value;
    var xhr = new XMLHttpRequest();
    xhr.open("GET", `${API_BASE}php/mensajes/obtener_mensajes.php?producto_id=` + productoId, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        document.getElementById("chat-box").innerHTML = xhr.responseText;
        document.getElementById("chat-box").scrollTop =
          document.getElementById("chat-box").scrollHeight;
      }
    };
    xhr.send();
  }

  document.getElementById("chat-form").addEventListener("submit", function (e) {
    e.preventDefault();
    var mensaje = document.getElementById("mensaje").value;
    var productoId = document.getElementById("producto_id").value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", `${API_BASE}php/mensajes/enviar_mensaje_chat.php`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        document.getElementById("mensaje").value = "";
        cargarmensajes();
      }
    };
    xhr.send("producto_id=" + productoId + "&mensaje=" + mensaje);
  });

  setInterval(cargarmensajes, 3000);
  cargarmensajes();
})();
