<?php
require 'php/obtener_mensajes_usuario.php';
$mensajes = include 'php/obtener_mensajes_usuario.php';
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
    <header>
        <h1>Tus mensajes</h1>
        <nav>
            <ul>
                <li><a href="Principal.php">Inicio</a></li>
                <li><a href="Productos.php">productos</a></li>
                <li><a href="Categorias.php">Categorías</a></li>
                <li><a href="carrito.php" onclick="return checkSession('carrito.php');">Carrito</a></li>
                <li><a href="php/cerrar_sesion.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
        <div class="profile-container">
            <div class="user-profile" onclick="toggleDropdown(event)">
                <img src="<?php echo isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) ? 'data:image/jpeg;base64,' . $_SESSION['avatar'] : 'Recursos/default.jpg'; ?>"
                    alt="Avatar" class="profile-avatar">
                <div class="profile-info">
                    <p class="profile-name">
                        <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Nombre'; ?>
                    </p>
                    <p class="profile-role"><?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'Rol'; ?></p>
                </div>
            </div>
            <div id="profile-dropdown" class="profile-dropdown">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="Perfil.php" onclick="return checkSession('Perfil.php')">Revisar perfil</a>
                    <a href="Mensajes.php" onclick="return checkSession('Mensajes.php')">mensajes</a>
                    <a href="php/cerrar_sesion.php">Cerrar sesión</a>
                <?php else: ?>
                    <a href="login.php">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
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