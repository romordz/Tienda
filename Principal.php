<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda en Línea</title>
    <link rel="stylesheet" href="css/SPrincipal.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Bienvenido a Nuestra Tienda</h1>
        <nav>
            <ul>
                <li><a href="Principal.php">Inicio</a></li>
                <li><a href="Productos.php">productos</a></li>
                <li><a href="Categorias.php">Categorías</a></li>
                <li><a href="carrito.php" onclick="return checkSession('carrito.php');">Carrito</a></li>
            </ul>
        </nav>
        <div class="search-container">
            <input type="text" id="search-input" placeholder="Buscar productos...">
            <button id="search-button" onclick="realizarBusqueda()">Buscar</button>
        </div>
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
                    <a href="Login.php">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <section class="product-list">
            <button id="mostrar-vendedores-button" onclick="obtenerVendedores()">Mostrar Vendedores</button>
            <div id="vendedores-container">
                <!-- Aquí se mostrarán los vendedores -->
            </div>
            <h2 id="search-heading" style="display: none;">Resultados de la busqueda</h2>
            <div id="search-results" class="search-results">
                <!-- Los resultados de búsqueda se cargarán aquí -->
            </div>
            <h2>productos Destacados</h2>
            <div class="products_destacados">
                <!-- Los productos destacados se cargarán aquí -->
            </div>
            <h2>productos Recientes</h2>
            <div class="products_recientes">
                <!-- Los productos recientes se cargarán aquí -->
            </div>

        </section>
    </main>
    <footer>
        <p>&copy; 2024 Tienda en Línea. Todos los derechos reservados.</p>
    </footer>
    <script src="js/sessionCheck.js"></script>
    <script src="js/productosObtener.js"></script>
    <script src="js/buscarProducto.js"></script>
    <script src="js/obtenerVendedores.js"></script>
</body>

</html>