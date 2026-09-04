<?php
require __DIR__ . '/../php/sesion/init.php'; 
require __DIR__ . '/../php/config.php';
require __DIR__ . '/../php/DB/conexion.php';

if (!isset($_SESSION['user_id'])) {
    echo "Error: No estás logueado. La variable de sesión no está disponible.";
    exit();
}

$usuario_id = $_SESSION['user_id'];
$producto_id = $_POST['producto_id'] ?? null;

if (!$producto_id) {
    header("Location: " . urlFor('pantallas/Principal.php'));
    exit();
}

include __DIR__ . '/../php/productos/get_producto_detalle.php';
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
    <link rel="stylesheet" href="<?= urlFor('css/SPrincipal.css') ?>">
    <link rel="stylesheet" href="<?= urlFor('css/SChat.css') ?>">
    <title>Chat con el Vendedor</title>
</head>

<body>
    <<?php require __DIR__ . '/../componentes/Header/Header.php'; ?>

    <section class="chat-section">
        <div class="chat-box" id="chat-box">

        </div>

        <form id="chat-form">
            <input type="hidden" id="producto_id" value="<?php echo $producto_id; ?>">
            <textarea id="mensaje" rows="3" placeholder="Escribe tu mensaje..." required></textarea>
            <button type="submit" class="Chat-Enviar">Enviar</button>
        </form>
    </section>

    <?php require __DIR__ . '/../componentes/Footer/Footer.php'; ?>

    <script src="<?= urlFor('js/cargarMensajes.js') ?>"></script>
</body>
</html>