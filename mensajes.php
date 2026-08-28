<?php
require 'php/obtener_mensajes_usuario.php';
$mensajes = include 'php/obtener_mensajes_usuario.php';

$page_title = "Detalle del Producto";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mensajes</title>
    <link rel="stylesheet" href="css/SPrincipal.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php require 'componentes/Header/Header.php'; ?>
    <main>
        <section class="message-list">
            <h2>mensajes Recibidos</h2>
            <?php if (empty($mensajes)): ?>
                <p>No tienes mensajes nuevos.</p>
            <?php else: ?>
                <?php foreach ($mensajesAgrupados as $conversacion): ?>
                    <div class="message-item">
                        <div class="product-info">
                            <p><strong>Producto:</strong> <?php echo htmlspecialchars($conversacion['producto_nombre']); ?></p>
                        </div>
                        <div class="chat-bubble-container">
                            <?php foreach ($conversacion['conversacion'] as $mensaje): ?>
                                <div
                                    class="chat-bubble <?php echo ($mensaje['usuario_id'] === $user_id) ? 'sent' : 'received'; ?>">
                                    <p><strong>De:</strong> <?php echo htmlspecialchars($mensaje['remitente_nombre']); ?></p>
                                    <p><?php echo htmlspecialchars($mensaje['mensaje']); ?></p>
                                    <em><?php echo $mensaje['fecha']; ?></em>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <form action="mensaje_vendedor.php" method="POST">
                            <input type="hidden" name="producto_id" value="<?php echo $mensaje['producto_id']; ?>">
                            <button type="submit" class="Message-Ver">Ver Conversación</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Tienda en Línea. Todos los derechos reservados.</p>
    </footer>
    <script src="js/sessionCheck.js"></script>
</body>
</html>