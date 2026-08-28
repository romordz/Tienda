<?php
session_start();
require __DIR__ . '/php/DB/conexion.php';

if (!isset($_SESSION['user_id'])) {
    echo "Error: No estás logueado. La variable de sesión no está disponible.";
    exit();
}

$usuario_id = $_SESSION['user_id'];
$producto_id = $_POST['producto_id'] ?? null;

if (!$producto_id) {
    header("Location: Principal.php");
    exit();
}

include __DIR__ . '/php/productos/get_producto_detalle.php';
$producto = obtener_producto_detalle($producto_id);

if (!$producto || empty($producto['producto']['nombre'])) {
    $producto_nombre = "Producto no encontrado";
} else {
    $producto_nombre = htmlspecialchars($producto['producto']['nombre']);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/SPrincipal.css">
    <link rel="stylesheet" href="css/SChat.css">
    <title>Chat con el Vendedor</title>
</head>

<body>
    <header>
        <h1>Chat sobre <?php echo $producto_nombre; ?></h1>
    </header>

    <section class="chat-section">
        <div class="chat-box" id="chat-box">
            <!-- Aquí cargaremos los mensajes con AJAX -->
        </div>

        <form id="chat-form">
            <input type="hidden" id="producto_id" value="<?php echo $producto_id; ?>">
            <textarea id="mensaje" rows="3" placeholder="Escribe tu mensaje..." required></textarea>
            <button type="submit" class="Chat-Enviar">Enviar</button>
        </form>
    </section>

    <?php require 'componentes/Footer/Footer.php'; ?>

    <script src="js/cargarMensajes.js"></script>
    <!-- <script>
        function cargarmensajes() {
            var productoId = document.getElementById('producto_id').value;
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "php/mensajes/obtener_mensajes.php?producto_id=" + productoId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('chat-box').innerHTML = xhr.responseText;
                    document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
                }
            };
            xhr.send();
        }

        document.getElementById('chat-form').addEventListener('submit', function (e) {
            e.preventDefault();
            var mensaje = document.getElementById('mensaje').value;
            var productoId = document.getElementById('producto_id').value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "php/mensajes/enviar_mensaje_chat.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('mensaje').value = '';
                    cargarmensajes();
                }
            };
            xhr.send("producto_id=" + productoId + "&mensaje=" + mensaje);
        });

        setInterval(cargarmensajes, 3000);
        cargarmensajes();
    </script> -->
</body>
</html>