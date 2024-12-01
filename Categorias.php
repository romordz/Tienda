<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/SPrincipal.css">
    <link rel="stylesheet" href="css/SCategorias.css">
    <title>Categorías</title>
</head>

<body>
    <header>
        <h1>Categorías de Productos</h1>
        <nav>
            <ul>
                <li><a href="Principal.php">Inicio</a></li>
                <li><a href="productos.php">Productos</a></li>
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
                <a href="Perfil.php" onclick="return checkSession('Perfil.php')">Revisar perfil</a>
                <a href="mensajes.php" onclick="return checkSession('mensajes.php')">Mensajes</a>
                <a href="php/cerrar_sesion.php">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <section class="category-list">
    <h2>Explora nuestras categorías</h2>
    <div id="categories-container" class="categories"></div>
</section>


    <footer>
        <p>© 2024 Tu Tienda. Todos los derechos reservados.</p>
    </footer>
    <script src="js/getCategorias.js"></script>
    <script src="js/sessionCheck.js"></script>
</body>
</html>
